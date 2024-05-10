<?php namespace IdkChat\Database;

use PDO;
use PDOException;

include_once "BaseDatabaseAdapter.php";

class SqliteDatabaseAdapter extends BaseDatabaseAdapter {
    private PDO|null $pdo = null;

    function getPDO(): PDO {
        return $this->pdo;
    }

    public function connect(string $host, ?string $user, ?string $password, ?string $database): void {
        if($this->pdo != null) {
            return;
        }

        $SETUP = [
            "CREATE TABLE IF NOT EXISTS `users` (
                `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                `login` VARCHAR(127) UNIQUE,
                `password` VARCHAR(127)
            );",
            "CREATE TABLE IF NOT EXISTS `messages` (
                `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                `user_id` BIGINT NOT NULL,
                `text` LONGTEXT,
                `time` INT NOT NULL,
                FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
            );",
        ];

        try {
            $this->pdo = new PDO("sqlite:$host");
        } catch (PDOException $e) {
            error_log($e);
            echo "Failed to connect to database!";
            die;
        }

        $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);

        try {
            foreach ($SETUP as $setup) {
                $this->pdo->exec($setup);
            }
        } catch (PDOException $e) {
            error_log($e);
            echo "Failed to set up database!";
            die;
        }
    }
}

if($GLOBALS["db_autoconnect"]) {
    SqliteDatabaseAdapter::getInstance()->connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_password"], $GLOBALS["db_database"]);
}
