<?php

namespace IdkChat\Database\Models;

require_once $GLOBALS["ROOT_DIR"]."/config.php";
include_once $GLOBALS["DB_ADAPTER_PATH"];

class User {
    private int $id;
    private string $login;
    private string $password_hash;

    function __construct(int $id, string $login, string $password_hash) {
        $this->id = $id;
        $this->login = $login;
        $this->password_hash = $password_hash;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getLogin(): string {
        return $this->login;
    }

    public function validatePassword(string $password): bool {
        return password_verify($password, $this->password_hash);
    }
}

class UserFactory {
    static function create(string $login, string $password): User {
        $db = $GLOBALS["DB_ADAPTER_CLASS"]::getInstance();
        $id = $db->addUser($login, $password);
        $raw = $db->getUserRaw($id);

        return new User($id, $login, $raw[2]);
    }

    static function get(int $user_id): User | null {
        $db = $GLOBALS["DB_ADAPTER_CLASS"]::getInstance();
        $raw = $db->getUserRaw($user_id);
        if($raw == null)
            return null;

        return new User($user_id, $raw[1], $raw[2]);
    }

    static function getByLogin(string $login): User | null {
        $db = $GLOBALS["DB_ADAPTER_CLASS"]::getInstance();
        $id = $db->getIdByLogin($login);
        if($id == null)
            return null;

        return UserFactory::get($id);
    }
}