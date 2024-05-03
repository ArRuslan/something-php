<?php
if(!isset($_POST["user_login"])) {
    header("Location: /delete_user");
    die;
}

include_once "../config.php";
include_once "../Database.php";

$db = new DatabaseClass\Database($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_password"], $GLOBALS["db_database"]);
$db->deleteUserByLogin($_POST["user_login"]);

header("Location: /delete_user");