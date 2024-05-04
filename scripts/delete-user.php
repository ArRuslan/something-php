<?php
if(!isset($_POST["user_login"])) {
    header("Location: /admin/delete-user");
    die;
}

include_once $GLOBALS["ROOT_DIR"]."/config.php";
include_once $GLOBALS["ROOT_DIR"]."/Database.php";

$db = new IdkChat\DatabaseClass\Database($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_password"], $GLOBALS["db_database"]);
$db->deleteUserByLogin($_POST["user_login"]);

header("Location: /admin/delete-user");