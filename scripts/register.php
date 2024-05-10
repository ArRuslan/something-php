<?php

use IdkChat\Database\Models\UserFactory;
use IdkChat\Lib\DbFactoryLoggingDecorator;

if(!isset($_POST["login"]) || !isset($_POST["password"])) {
    header("Location: /auth");
    die;
}

include_once $GLOBALS["ROOT_DIR"]."/config.php";
include_once $GLOBALS["ROOT_DIR"]."/db/models/User.php";
include_once $GLOBALS["ROOT_DIR"]."/lib/DbFactoryLoggingDecorator.php";

try {
    (new DbFactoryLoggingDecorator(UserFactory::class))->create($_POST["login"], $_POST["password"]);
    //UserFactory::create($_POST["login"], $_POST["password"]);
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        die("
            <html lang=\"en\">
                <head>
                    <meta http-equiv=\"refresh\" content=\"3;url=/auth\" />
                    <title>Register</title>
                </head>
                <body>
                    <h1>User with same login already exists!</h1>
                    <a href='/auth'>Redirecting in 3 seconds...</a>
                </body>
            </html>
        ");
    }
    echo $e;
    die;
}

session_start();

$_SESSION["login"] = $_POST["login"];

header("Location: /dialogs");
