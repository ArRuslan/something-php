<?php

use IdkChat\Database\Models\UserFactory;

if(!isset($_POST["login"]) || !isset($_POST["password"])) {
    header("Location: /auth");
    die;
}

include_once $GLOBALS["ROOT_DIR"]."/config.php";
include_once $GLOBALS["ROOT_DIR"]."/db/models/User.php";

$user = UserFactory::getByLogin($_POST["login"]);
if($user == null || !$user->validatePassword($_POST["password"])) {
    die("
            <html lang=\"en\">
                <head>
                    <meta http-equiv=\"refresh\" content=\"3;url=/auth\" />
                    <title>Login</title>
                </head>
                <body>
                    <h1>Wrong login or password!</h1>
                    <a href='/auth'>Redirecting in 3 seconds...</a>
                </body>
            </html>
        ");
}

session_start();

$_SESSION["login"] = $_POST["login"];

header("Location: /dialogs");
