<?php namespace IdkChat\Database;

include_once $GLOBALS["ROOT_DIR"]."/lib/Singleton.php";

use IdkChat\Lib\Singleton;
use PDO;

abstract class BaseDatabaseAdapter extends Singleton {
    public abstract function getPDO(): PDO;

    public abstract function connect(String $host, ?String $user, ?String $password, ?String $database): void;
}