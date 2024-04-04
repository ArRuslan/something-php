<?php
if(!isset($_POST["login"]) || !isset($_POST["password"])) {
    header("Location: /auth");
    die;
}

include "config.php";
include "Database.php";

$db = new Database($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_password"], $GLOBALS["db_database"]);
if(!$db->checkUserPassword($_POST["login"], $_POST["password"])) {
    // TODO: show user an error
    header("Location: /auth");
    die;
}

session_start();

$_SESSION["login"] = $_POST["login"];

header("Location: /dialogs");
