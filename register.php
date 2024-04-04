<?php
if(!isset($_POST["login"]) || !isset($_POST["password"])) {
    header("Location: /auth");
    die;
}

include "config.php";
include "Database.php";

// TODO: handle errors
$db = new Database($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_password"], $GLOBALS["db_database"]);
$db->addUser($_POST["login"], $_POST["password"]);
$db->close();

session_start();

$_SESSION["login"] = $_POST["login"];

header("Location: /dialogs");
