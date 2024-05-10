<?php namespace IdkChat\Database;

include_once $GLOBALS["ROOT_DIR"]."/lib/Singleton.php";
use IdkChat\Lib\Singleton;

abstract class BaseDatabaseAdapter extends Singleton {
    abstract function getPDO(): \PDO;

    public abstract function connect(String $host, ?String $user, ?String $password, ?String $database): void;

    public function getIdByLogin(String $login): ?String {
        $get_user_stmt = $this->getPDO()->prepare("SELECT `id` FROM `users` WHERE `login`=:login;");
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
        $stmt = $this->getPDO()->prepare($queryToDeleteUsersMessages);
        $stmt->execute([":id" => $id]);

        //delete user
        $queryToDeleteUser = "DELETE FROM `users` WHERE `id`=:id;";
        $stmt = $this->getPDO()->prepare($queryToDeleteUser);;
        $stmt->execute([":id" => $id]);;
        return true;
    }

    public function getAllUsers(): array|null {
        $userQuery = $this->getPDO()->prepare("SELECT `login` FROM `users`");
        $userQuery->execute();
        $users = $userQuery->fetchAll();
        if (!$users) {
            return null;
        }

        return $users;
    }

    public function addUser(String $login, String $password): void {
        $insert_user_stmt = $this->getPDO()->prepare("INSERT INTO `users` (`login`, `password`) VALUES (:login, :password);");
        $insert_message_stmt = $this->getPDO()->prepare("INSERT INTO `messages` (`user_id`, `text`, `time`) VALUES (:user_id, :text, :time);");

        $password = password_hash($password, PASSWORD_BCRYPT);

        $messages = [
            "Welcome to IdkChat!",
            "This is your personal chat",
            "But you can create dialogs with other users by pressing \"New dialog\" button!",
        ];

        $insert_user_stmt->execute([":login" => $login, ":password" => $password]);
        $id = $this->getIdByLogin($login);

        $this->getPDO()->beginTransaction();
        try {
            foreach ($messages as $message) {
                $insert_message_stmt->execute([":user_id" => $id, ":text" => $message, ":time" => time()]);
            }
            $this->getPDO()->commit();
        } catch (\PDOException $e) {
            $this->getPDO()->rollBack();
            throw $e;
        }
    }

    public function checkUserPassword(String $login, String $password): bool {
        $get_pwd_stmt = $this->getPDO()->prepare("SELECT `password` FROM `users` WHERE `login`=:login;");
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

        $insert_message_stmt = $this->getPDO()->prepare("INSERT INTO `messages` (`user_id`, `text`, `time`) VALUES (:user_id, :text, :time);");
        $insert_message_stmt->execute([":user_id" => $id, ":text" => $text, ":time" => time()]);
    }

    public function getUserMessages(String $login): array {
        $result = array();
        $id = $this->getIdByLogin($login);
        if($id === null)
            return $result;

        $get_messages_stmt = $this->getPDO()->prepare("SELECT `text`, `time` FROM `messages` WHERE `user_id`=:user_id;");
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