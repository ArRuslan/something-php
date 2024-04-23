<?php

$TABLES = [
    "CREATE TABLE IF NOT EXISTS `users` (
        `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
        `login` VARCHAR(128) UNIQUE,
        `password` VARCHAR(128)
    );",
    "CREATE TABLE IF NOT EXISTS `messages` (
        `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
        `user_id` BIGINT NOT NULL,
        `text` LONGTEXT,
        `time` INT NOT NULL,
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
    );",
];

class Database {
    private PDO $pdo;

    public function __construct(String $host, String $user, String $password, String $database) {
        global $TABLES;
        $this->pdo = new PDO("mysql:host=$host;dbname=$database;charset=UTF8", $user, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        foreach ($TABLES as $table) {
            $this->pdo->exec($table);
        }
    }

    public function getIdByLogin(String $login): ?String {
        $get_user_stmt = $this->pdo->prepare("SELECT `id` FROM `users` WHERE `login`=:login;");
        $get_user_stmt->execute([":login" => $login]);
        $user = $get_user_stmt->fetch(PDO::FETCH_ASSOC);
        if(!$user)
            return null;

        return $user["id"];
    }

    public function addUser(String $login, String $password): void {
        $insert_user_stmt = $this->pdo->prepare("INSERT INTO `users` (`login`, `password`) VALUES (:login, :password);");
        $password = password_hash($password, PASSWORD_BCRYPT);
        $insert_user_stmt->execute([":login" => $login, ":password" => $password]);
    }

    public function checkUserPassword(String $login, String $password): bool {
        $get_pwd_stmt = $this->pdo->prepare("SELECT `password` FROM `users` WHERE `login`=:login;");
        $get_pwd_stmt->execute([":login" => $login]);
        $user = $get_pwd_stmt->fetch(PDO::FETCH_ASSOC);
        if(!$user)
            return false;

        return password_verify($password, $user["password"]);
    }

    public function addMessage(String $login, String $text): void {
        $id = $this->getIdByLogin($login);
        if($id === null)
            return;

        $insert_message_stmt = $this->pdo->prepare("INSERT INTO `messages` (`user_id`, `text`, `time`) VALUES (:user_id, :text, :time);");
        $insert_message_stmt->execute([":user_id" => $id, ":text" => $text, ":time" => time()]);
    }

    public function getUserMessages(String $login): array {
        $result = array();
        $id = $this->getIdByLogin($login);
        if($id === null)
            return $result;

        $get_messages_stmt = $this->pdo->prepare("SELECT `text`, `time` FROM `messages` WHERE `user_id`=:user_id;");
        $get_messages_stmt->execute([":user_id" => $id]);

        while($message = $get_messages_stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($result, array(
                "text" => $message["text"],
                "time" => date("d.m.Y H:i:s", $message["time"]),
            ));
        }

        return $result;
    }
}