<?php namespace DatabaseClass;

class Database {
    private \PDO $pdo;

    public function __construct(String $host, String $user, String $password, String $database) {
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
            $this->pdo = new \PDO("mysql:host=$host;dbname=$database;charset=UTF8", $user, $password);
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

    public function getIdByLogin(String $login): ?String {
        $get_user_stmt = $this->pdo->prepare("SELECT `id` FROM `users` WHERE `login`=:login;");
        $get_user_stmt->execute([":login" => $login]);
        $user = $get_user_stmt->fetch(\PDO::FETCH_ASSOC);
        if(!$user)
            return null;

        return $user["id"];
    }

    public function deleteUserByLogin(string $login): bool {
        $id = $this->getIdByLogin($login);
        if ($id == null) {
            echo "User with this login was not found! Operation cannot be finished successfully";
            return false;
        }
        //delete user messages
        $queryToDeleteUsersMessages = "DELETE FROM `messages` WHERE `user_id`=:id;";
        $stmt = $this->pdo->prepare($queryToDeleteUsersMessages);
        $stmt->execute([":id" => $id]);

        //delete user
        $queryToDeleteUser = "DELETE FROM `users` WHERE `id`=:id;";
        $stmt = $this->pdo->prepare($queryToDeleteUser);;
        $stmt->execute([":id" => $id]);;
        return true;
    }

    public function getAllUsers(): array|null {
        $userQuery = $this->pdo->prepare("SELECT `login` FROM `users`");
        $userQuery->execute();
        $users = $userQuery->fetchAll();
        if (!$users) {
            return null;
        }

        return $users;
    }

    public function addUser(String $login, String $password): void {
        $insert_user_stmt = $this->pdo->prepare("INSERT INTO `users` (`login`, `password`) VALUES (:login, :password);");
        $insert_message_stmt = $this->pdo->prepare("INSERT INTO `messages` (`user_id`, `text`, `time`) VALUES (:user_id, :text, :time);");

        $password = password_hash($password, PASSWORD_BCRYPT);

        $messages = [
            "Welcome to IdkChat!",
            "This is your personal chat",
            "But you can create dialogs with other users by pressing \"New dialog\" button!",
        ];

        $this->pdo->beginTransaction();
        try {
            $insert_user_stmt->execute([":login" => $login, ":password" => $password]);
            $id = $this->getIdByLogin($login);
            foreach ($messages as $message) {
                $insert_message_stmt->execute([":user_id" => $id, ":text" => $message, ":time" => time()]);
            }
            $this->pdo->commit();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function checkUserPassword(String $login, String $password): bool {
        $get_pwd_stmt = $this->pdo->prepare("SELECT `password` FROM `users` WHERE `login`=:login;");
        $get_pwd_stmt->execute([":login" => $login]);
        $user = $get_pwd_stmt->fetch(\PDO::FETCH_ASSOC);
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

        while($message = $get_messages_stmt->fetch(\PDO::FETCH_ASSOC)) {
            array_push($result, array(
                "text" => $message["text"],
                "time" => date("d.m.Y H:i:s", $message["time"]),
            ));
        }

        return $result;
    }
}