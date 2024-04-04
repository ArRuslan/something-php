<?php

session_start();

if(!isset($_POST["text"]) || !isset($_SESSION["login"])) {
    header("Location: /auth");
    die;
}

include "config.php";
include "Database.php";

$db = new Database($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_password"], $GLOBALS["db_database"]);
$db->addMessage($_SESSION["login"], $_POST["text"]);
$db->close();

header("Location: /dialogs");