<?php

session_start();

if(!isset($_POST["text"]) || !isset($_SESSION["login"])) {
    header("Location: /auth");
    die;
}

include "config.php";
include "Database.php";

global $db_database, $db_host, $db_user, $db_password;

$db = new Database($db_host, $db_user, $db_password, $db_database);
$db->addMessage($_SESSION["login"], $_POST["text"]);
$db->close();

header("Location: /dialogs");