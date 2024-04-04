<?php
if(!isset($_POST["login"]) || !isset($_POST["password"])) {
    header("Location: /auth");
    die;
}

include "config.php";
include "Database.php";

global $db_database, $db_host, $db_user, $db_password;

// TODO: handle errors
$db = new Database($db_host, $db_user, $db_password, $db_database);
$db->addUser($_POST["login"], $_POST["password"]);
$db->close();

session_start();

$_SESSION["login"] = $_POST["login"];

header("Location: /dialogs");
