<?php
if(!isset($_POST["login"]) || !isset($_POST["password"])) {
    header("Location: /auth");
    die;
}

include "../config.php";
include "../Database.php";

$db = new DatabaseClass\Database($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_password"], $GLOBALS["db_database"]);
if(!$db->checkUserPassword($_POST["login"], $_POST["password"])) {
    die("
            <html>
                <head>
                    <meta http-equiv=\"refresh\" content=\"3;url=/auth\" />
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
