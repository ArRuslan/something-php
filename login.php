<?php
if(!isset($_POST["login"]) || !isset($_POST["password"])) {
    header("Location: /auth");
    die;
}

include "Database.php";

$db = new Database("127.0.0.1", "idkchatphp", "123456789", "idkchatphp");
if(!$db->checkUserPassword($_POST["login"], $_POST["password"])) {
    header("Location: /auth");
    die;
}

session_start();

$_SESSION["login"] = $_POST["login"];

header("Location: /dialogs");
