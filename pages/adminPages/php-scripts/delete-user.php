<?php


include(dirname(__DIR__)."\..\..\config.php");
include(dirname(__DIR__)."\..\..\Database.php");

$db = new Database($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_password"], $GLOBALS["db_database"]);
$db->deleteUserByLogin($_POST["user_login"]);

header("Location: /delete_user");