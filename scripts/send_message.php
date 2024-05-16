<?php

use IdkChat\Database\Models\MessageFactory;
use IdkChat\Database\Models\UserFactory;

session_start();

if(!isset($_POST["text"]) || !isset($_SESSION["login"])) {
    header("Location: /auth");
    die;
}

include_once $GLOBALS["ROOT_DIR"]."/config.php";
include_once $GLOBALS["DB_ADAPTER_PATH"];

$db = $GLOBALS["DB_ADAPTER_CLASS"]::getInstance();
$db->connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_password"], $GLOBALS["db_database"]);

$user = UserFactory::getByLogin($_SESSION["login"]);
if($user == null) {
    header("Location: /api/logout");
    die;
}
MessageFactory::create($user->getId(), $_POST["text"]);

header("Location: /dialogs");