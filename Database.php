<?php

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

    public function deleteUserByLogin(String $login): bool
    {
        $id = $this->getIdByLogin($login);
        try
        {
            if ($id == null)
            {
                throw new InvalidArgumentException("User with this login was not found! Operation cannot be finished successfully");
            }
        }
        catch(InvalidArgumentException $exc)
        {
            echo $exc->getMessage();
            return false;
        }
        //delete user messages
        $queryToDeleteUsersMessages = "DELETE FROM `messages` WHERE `user_id` LIKE ?";
        $stmt = $this->connection->prepare($queryToDeleteUsersMessages);
        $stmt->bind_param("s",$id);
        $stmt->execute();

        //delete user
        $queryToDeleteUser = "DELETE FROM `users` WHERE `id` LIKE ?";
        $stmt = $this->connection->prepare($queryToDeleteUser);
        $stmt->bind_param("s",$id);
        $stmt->execute();
        return true;
    }

    public function getAllUsers() : array | null
    {
        $userArray = mysqli_query($this->connection, "SELECT `login` FROM `users`");
        if ($userArray -> num_rows == 0)
        {
            return null;
        }

        $fetchedAssocArray =  mysqli_fetch_all($userArray);
        return $fetchedAssocArray;
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