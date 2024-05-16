<?php

$GLOBALS["db_adapter"] = "MysqlDatabaseAdapter"; // MysqlDatabaseAdapter or SqliteDatabaseAdapter
//$GLOBALS["db_host"] = $_ENV["DATABASE_HOST"]; // Host for mysql adapter, path for sqlite
$GLOBALS["db_host"] = "localhost:3306"; // Host for mysql adapter, path for sqlite
$GLOBALS["db_user"] = "root"; // Only used for mysql adapter
$GLOBALS["db_password"] = "IronSword12"; // Only used for mysql adapter
$GLOBALS["db_database"] = "idkchatphp"; // Only used for mysql adapter
$GLOBALS["db_autoconnect"] = true;
$GLOBALS["jwt_key"] = "asd";

$GLOBALS["ROOT_DIR"] = dirname(__FILE__);
$GLOBALS["DB_ADAPTER_PATH"] = $GLOBALS["ROOT_DIR"] . "/db/" . $GLOBALS["db_adapter"] . ".php";
$GLOBALS["DB_ADAPTER_CLASS"] = ("IdkChat\\Database\\".$GLOBALS["db_adapter"]);