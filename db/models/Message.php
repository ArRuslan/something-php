<?php

namespace IdkChat\Database\Models;

require_once $GLOBALS["ROOT_DIR"]."/config.php";
include_once "BaseFactory.php";
include_once $GLOBALS["DB_ADAPTER_PATH"];

class Message {
    private int $id;
    private string $text;
    private int $time;

    function __construct(int $id, string $text, int $time) {
        $this->id = $id;
        $this->text = $text;
        $this->time = $time;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getText(): string {
        return $this->text;
    }

    public function getTime(): int {
        return $this->time;
    }

    public function getTimeFormatted(): string {
        return date("d.m.Y H:i:s", $this->time);
    }
}

class MessageFactory implements BaseFactory {
    static function create(int $user_id, string $text): Message {
        $time = time();

        $pdo = $GLOBALS["DB_ADAPTER_CLASS"]::getInstance()->getPDO();
        $insert_message_stmt = $pdo->prepare("INSERT INTO `messages` (`user_id`, `text`, `time`) VALUES (:user_id, :text, :time);");
        $insert_message_stmt->execute([":user_id" => $user_id, ":text" => $text, ":time" => $time]);

        return new Message($pdo->lastInsertId(), $text, $time);
    }
}