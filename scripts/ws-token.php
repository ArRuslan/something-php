<?php

session_start();
if (!isset($_SESSION["login"])) {
    http_response_code(401);
    die;
}

include "../config.php";
include_once "../lib/jwt.php";

$jwt = new Lib\JWT($GLOBALS["jwt_key"]);

echo json_encode([
    "auth_token" => $jwt->encode([
        "login" => $_SESSION["login"]
    ])
]);