<?php

session_start();

if(!isset($_POST["text"]) || !isset($_SESSION["login"])) {
    header("Location: /auth");
    die;
}

include "Database.php";

$db = new Database("127.0.0.1", "idkchatphp", "123456789", "idkchatphp");
$db->addMessage($_SESSION["login"], $_POST["text"]);
$db->close();

header("Location: /dialogs");