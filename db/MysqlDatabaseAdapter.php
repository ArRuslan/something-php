<?php namespace IdkChat\Database;

use PDO;

include_once "BaseDatabaseAdapter.php";

class MysqlDatabaseAdapter extends BaseDatabaseAdapter {
    private PDO|null $pdo = null;

    function getPDO(): PDO {
        return $this->pdo;
    }

    public function connect(String $host, ?String $user, ?String $password, ?String $database): void {
        if($this->pdo != null) {
            return;
        }

        $SETUP = [
            "CREATE DATABASE IF NOT EXISTS `$database`;",
            "CREATE TABLE IF NOT EXISTS `users` (
                `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
                `login` VARCHAR(127) UNIQUE,
                `password` VARCHAR(127)
            );",
            "CREATE TABLE IF NOT EXISTS `messages` (
                `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
                `user_id` BIGINT NOT NULL,
                `text` LONGTEXT,
                `time` INT NOT NULL,
                FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
            );",
        ];

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$database;charset=UTF8", $user, $password);
        } catch(\PDOException $e) {
            echo "Failed to connect to database!";
            die;
        }

        try {
            foreach ($SETUP as $setup) {
                $this->pdo->exec($setup);
            }
        } catch(\PDOException $e) {
            echo "Failed to set up database!";
            die;
        }
    }
}

if($GLOBALS["db_autoconnect"]) {
    MysqlDatabaseAdapter::getInstance()->connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_password"], $GLOBALS["db_database"]);
}
