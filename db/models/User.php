<?php

namespace IdkChat\Database\Models;

require_once $GLOBALS["ROOT_DIR"]."/config.php";
include_once "BaseFactory.php";
include_once "Message.php";
include_once $GLOBALS["DB_ADAPTER_PATH"];

class User {
    private int $id;
    private string $login;
    private string $password_hash;

    function __construct(int $id, string $login, string $password_hash) {
        $this->id = $id;
        $this->login = $login;
        $this->password_hash = $password_hash;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getLogin(): string {
        return $this->login;
    }

    public function validatePassword(string $password): bool {
        return password_verify($password, $this->password_hash);
    }

    public function delete(): void {
        $pdo = $GLOBALS["DB_ADAPTER_CLASS"]::getInstance()->getPDO();

        $queryToDeleteUsersMessages = "DELETE FROM `messages` WHERE `user_id`=:id;";
        $stmt = $pdo->prepare($queryToDeleteUsersMessages);
        $stmt->execute([":id" => $this->id]);

        $queryToDeleteUser = "DELETE FROM `users` WHERE `id`=:id;";
        $stmt = $pdo->prepare($queryToDeleteUser);
        $stmt->execute([":id" => $this->id]);
    }

    public function getMessages(): array {
        $result = array();

        $pdo = $GLOBALS["DB_ADAPTER_CLASS"]::getInstance()->getPDO();
        $get_messages_stmt = $pdo->prepare("SELECT `id`, `text`, `time` FROM `messages` WHERE `user_id`=:user_id;");
        $get_messages_stmt->execute([":user_id" => $this->id]);

        while($message = $get_messages_stmt->fetch(\PDO::FETCH_ASSOC)) {
            $result[] = new Message($message["id"], $message["text"], $message["time"]);
        }

        return $result;
    }
}

class UserFactory implements BaseFactory {
    static function create(string $login, string $password): User {
        $password = password_hash($password, PASSWORD_BCRYPT);

        $pdo = $GLOBALS["DB_ADAPTER_CLASS"]::getInstance()->getPDO();
        $insert_user_stmt = $pdo->prepare("INSERT INTO `users` (`login`, `password`) VALUES (:login, :password);");
        $insert_user_stmt->execute([":login" => $login, ":password" => $password]);

        return UserFactory::getByLogin($login);
    }

    static function get(int $user_id): User | null {
        $pdo = $GLOBALS["DB_ADAPTER_CLASS"]::getInstance()->getPDO();
        $get_user_stmt = $pdo->prepare("SELECT `login`, `password` FROM `users` WHERE `id`=:user_id;");
        $get_user_stmt->execute([":user_id" => $user_id]);
        $user = $get_user_stmt->fetch(\PDO::FETCH_ASSOC);
        if(!$user)
            return null;

        return new User($user_id, $user["login"], $user["password"]);
    }

    static function getByLogin(string $login): User | null {
        $pdo = $GLOBALS["DB_ADAPTER_CLASS"]::getInstance()->getPDO();
        $get_user_stmt = $pdo->prepare("SELECT `id`, `password` FROM `users` WHERE `login`=:login;");
        $get_user_stmt->execute([":login" => $login]);
        $user = $get_user_stmt->fetch(\PDO::FETCH_ASSOC);
        if(!$user)
            return null;

        return new User($user["id"], $login, $user["password"]);
    }

    static function getAll(): array | null {
        $pdo = $GLOBALS["DB_ADAPTER_CLASS"]::getInstance()->getPDO();

        $userQuery = $pdo->prepare("SELECT `login` FROM `users`");
        $userQuery->execute();
        $users = $userQuery->fetchAll();
        if (!$users) {
            return null;
        }

        return $users;
    }
}