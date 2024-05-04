<?php

session_start();
if (!isset($_SESSION["login"])) {
    http_response_code(401);
    die;
}
include_once $GLOBALS["ROOT_DIR"]."/config.php";
include_once $GLOBALS["ROOT_DIR"]."/lib/jwt.php";

$jwt = new IdkChat\Lib\JWT($GLOBALS["jwt_key"]);

echo json_encode([
    "auth_token" => $jwt->encode([
        "login" => $_SESSION["login"]
    ])
]);
