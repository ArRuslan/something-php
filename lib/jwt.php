<?php namespace Lib;

use InvalidArgumentException;
use stdClass;

class JWTException extends InvalidArgumentException {
}

trait ValidatesJWT {
    protected function validateConfig($key, int $maxAge): void {
        if (empty($key)) {
            throw new JWTException('Signing key cannot be empty', static::ERROR_KEY_EMPTY);
        }

        if ($maxAge < 1) {
            throw new JWTException('Invalid maxAge: Should be greater than 0', static::ERROR_INVALID_MAXAGE);
        }
    }

    protected function validateHeader(array $header): void {
        if (empty($header['alg'])) {
            throw new JWTException('Invalid token: Missing header algo', static::ERROR_ALGO_MISSING);
        }
        if ($header['alg'] !== "HS256") {
            throw new JWTException('Invalid token: Unsupported header algo', static::ERROR_ALGO_UNSUPPORTED);
        }
    }

    protected function validateTimestamps(array $payload): void {
        $timestamp = time();
        if(!isset($payload["exp"]) || $timestamp > $payload["exp"])
            throw new JWTException('Invalid token: ' . static::ERROR_TOKEN_EXPIRED, "Expired");
    }

    protected function validateLastJson(): void {
        if (JSON_ERROR_NONE === json_last_error()) {
            return;
        }

        throw new JWTException('JSON failed: ' . \json_last_error_msg(), static::ERROR_JSON_FAILED);
    }
}

class JWT {
    use ValidatesJWT;

    const ERROR_KEY_EMPTY = 10;
    const ERROR_ALGO_UNSUPPORTED = 20;
    const ERROR_ALGO_MISSING = 22;
    const ERROR_INVALID_MAXAGE = 30;
    const ERROR_JSON_FAILED = 40;
    const ERROR_TOKEN_INVALID = 50;
    const ERROR_TOKEN_EXPIRED = 52;
    const ERROR_SIGNATURE_FAILED = 60;

    protected string $key;
    protected int $maxAge = 60;

    /**
     * Constructor.
     *
     * @param string $key The signature key. For RS* it should be file path or resource of private key.
     * @param int $maxAge The TTL of token to be used to determine expiry if `iat` claim is present.
     *                                This is also used to provide default `exp` claim in case it is missing.
     */
    public function __construct(string $key, int $maxAge = 60) {
        $this->validateConfig($key, $maxAge);

        $this->key = $key;
        $this->maxAge = $maxAge;
    }

    /**
     * Encode payload as JWT token.
     *
     * @param array $payload
     *
     * @return string URL safe JWT token.
     */
    public function encode(array $payload): string {
        $header = ['typ' => 'JWT', 'alg' => "HS256"];
        $payload['exp'] = time() + $this->maxAge;

        $header = $this->urlSafeEncode($header);
        $payload = $this->urlSafeEncode($payload);
        $signature = $this->urlSafeEncode($this->sign($header . '.' . $payload));

        return $header . '.' . $payload . '.' . $signature;
    }

    /**
     * Decode JWT token and return original payload.
     *
     * @param string $token
     * @param bool $verify
     *
     * @return array
     * @throws JWTException
     *
     */
    public function decode(string $token): array {
        if (substr_count($token, '.') < 2) {
            throw new JWTException('Invalid token: Incomplete segments', static::ERROR_TOKEN_INVALID);
        }

        $token = explode('.', $token, 3);

        $this->validateHeader((array)$this->urlSafeDecode($token[0]));

        // Validate signature.
        if (!$this->verify($token[0] . '.' . $token[1], $token[2])) {
            throw new JWTException('Invalid token: Signature failed', static::ERROR_SIGNATURE_FAILED);
        }

        $payload = (array)$this->urlSafeDecode($token[1]);

        $this->validateTimestamps($payload);

        return $payload;
    }

    /**
     * Sign the input with configured key and return the signature.
     *
     * @param string $input
     *
     * @return string
     */
    protected function sign(string $input): string {
        return hash_hmac("sha256", $input, $this->key, true);
    }

    /**
     * Verify the signature of given input.
     *
     * @param string $input
     * @param string $signature
     *
     * @return bool
     * @throws JWTException When key is invalid.
     *
     */
    protected function verify(string $input, string $signature): bool {
        return hash_equals($this->urlSafeEncode(hash_hmac("sha256", $input, $this->key, true)), $signature);
    }

    /**
     * URL safe base64 encode.
     *
     * First serialized the payload as json if it is an array.
     *
     * @param array|string $data
     *
     * @return string
     * @throws JWTException When JSON encode fails.
     *
     */
    protected function urlSafeEncode(array | string $data): string {
        if (is_array($data)) {
            $data = json_encode($data, JSON_UNESCAPED_SLASHES);
            $this->validateLastJson();
        }

        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * URL safe base64 decode.
     *
     * @param array|string $data
     * @param bool $asJson Whether to parse as JSON (defaults to true).
     *
     * @return array|stdClass|string
     * @throws JWTException When JSON encode fails.
     *
     */
    protected function urlSafeDecode(array|string $data, bool $asJson = true): array|stdClass|string {
        if (!$asJson) {
            return base64_decode(strtr($data, '-_', '+/'));
        }

        $data = json_decode(base64_decode(strtr($data, '-_', '+/')));
        $this->validateLastJson();

        return $data;
    }
}