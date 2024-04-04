<?php
if(!isset($_POST["login"]) || !isset($_POST["password"])) {
    header("Location: /auth");
    die;
}

include "config.php";
include "Database.php";

global $db_database, $db_host, $db_user, $db_password;

$db = new Database($db_host, $db_user, $db_password, $db_database);
if(!$db->checkUserPassword($_POST["login"], $_POST["password"])) {
    // TODO: show user an error
    header("Location: /auth");
    die;
}

session_start();

$_SESSION["login"] = $_POST["login"];

header("Location: /dialogs");
