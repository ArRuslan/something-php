<?php

use IdkChat\Database\Models\UserFactory;

if(!isset($_POST["user_login"])) {
    header("Location: /admin/delete-user");
    die;
}

include_once $GLOBALS["ROOT_DIR"]."/config.php";
include_once $GLOBALS["ROOT_DIR"]."/db/models/User.php";
include_once $GLOBALS["DB_ADAPTER_PATH"];

$db = $GLOBALS["DB_ADAPTER_CLASS"]::getInstance();
$db->connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_password"], $GLOBALS["db_database"]);
UserFactory::getByLogin($_POST["user_login"])?->delete();

header("Location: /admin/delete-user");