<?php

$host = "127.0.0.1";
$user = "idkchatphp";
$password = "123456789";
$database = "idkchatphp";

class Database {
    private mysqli $connection;

    public function __construct(String $host, String $user, String $password, String $database) {
        $this->connection = mysqli_connect($host, $user, $password, $database);
        mysqli_query($this->connection, "CREATE TABLE IF NOT EXISTS `users` (`id` BIGINT AUTO_INCREMENT PRIMARY KEY, `login` VARCHAR(128) UNIQUE, `password` VARCHAR(128));");
        mysqli_query($this->connection, "CREATE TABLE IF NOT EXISTS `messages` (`id` BIGINT AUTO_INCREMENT PRIMARY KEY, `user_id` BIGINT NOT NULL, `text` LONGTEXT, `time` INT NOT NULL, FOREIGN KEY (`user_id`) REFERENCES `users`(`id`));");
    }

    public function close(): void {
        $this->connection->close();
    }

    public function __destruct() {
        $this->close();
    }

    public function getIdByLogin(String $login): ?String {
        $query = mysqli_query($this->connection, "SELECT `id` FROM `users` WHERE `login`='".$this->connection->real_escape_string($login)."';");
        if($query->num_rows == 0)
            return null;
        return mysqli_fetch_assoc($query)["id"];
    }

    public function addUser(String $login, String $password): void {
        $password = password_hash($password, PASSWORD_BCRYPT);
        mysqli_query($this->connection, "INSERT INTO `users` (`login`, `password`) VALUES ('".$this->connection->real_escape_string($login)."', '".$this->connection->real_escape_string($password)."');");
    }

    public function checkUserPassword(String $login, String $password): bool {
        $query = mysqli_query($this->connection, "SELECT `password` FROM `users` WHERE `login`='".$this->connection->real_escape_string($login)."';");
        if($query->num_rows === 0)
            return false;
        $row = mysqli_fetch_assoc($query);
        return password_verify($password, $row["password"]);
    }

    public function addMessage(String $login, String $text): void {
        $id = $this->getIdByLogin($login);
        if($id === null)
            return;
        mysqli_query($this->connection, "INSERT INTO `messages` (`user_id`, `text`, `time`) VALUES (".$id.", '".$this->connection->real_escape_string($text)."', ".time().");");
    }

    public function getUserMessages(String $login): array {
        $result = array();
        $id = $this->getIdByLogin($login);
        if($id === null)
            return $result;

        $query = mysqli_query($this->connection, "SELECT `text`, `time` FROM `messages` WHERE `user_id`='".$id."';");
        if($query->num_rows === 0)
            return $result;

        while($row = mysqli_fetch_assoc($query)) {
            array_push($result, array(
                "text" => $row["text"],
                "time" => date("d.m.Y H:i:s", $row["time"]),
            ));
        }

        return $result;
    }
}