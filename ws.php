<?php

require_once "config.php";
require_once "websockets.php";
require_once "jwt.php";
include_once "Database.php";

class ChatUser extends WebSocketUser {
    public String | null $login;

    function __construct(String $id, Socket $socket) {
        parent::__construct($id, $socket);
        $this->login = null;
    }
}

class echoServer extends WebSocketServer {
    protected array $charUsers = array();
    protected string $userClass = "ChatUser";
    protected JWT $jwt;
    protected DatabaseClass\Database $db;

    function __construct(String $addr, String $port, int $bufferLength = 2048) {
        parent::__construct($addr, $port, $bufferLength);
        $this->jwt = new JWT($GLOBALS["jwt_key"]);
        $this->db = new DatabaseClass\Database($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_password"], $GLOBALS["db_database"]);
    }

    protected function process ($user, String $message): void {
        if(!($user instanceof ChatUser)) {
            $this->disconnect($user->socket);
            return;
        }
        $data = json_decode($message, true);
        if(($data["op"] != "identify" && $user->login == null) || $data["op"] == "identify" && $user->login != null) {
            $this->disconnect($user->socket, true, 4003);
            return;
        }

        // -> {"op": "identify", "d": {"token": "some.jwt.token"}}
        // <- {"op": "ready", "d": null}
        // -> {"op": "message", "d": {"text": "test123"}}
        // <- {"op": "message", "d": {"text": "test123", "from": "test"}}

        switch($data["op"]) {
            case "identify": {
                try {
                    $data = $this->jwt->decode($data["d"]["token"]);
                } catch (JWTException $e) {
                    echo $e;
                    $this->disconnect($user->socket, true, 4001);
                    return;
                }

                $user->login = $data["login"];
                $this->charUsers[] = $user;
                $this->send($user, json_encode([
                    "op" => "ready",
                    "d" => null,
                ]));
                return;
            }
            case "broadcast":
            case "message": {
                $text = $data["d"]["text"];
                $this->db->addMessage($user->login, $text);

                foreach($this->charUsers as $u) {
                    $this->send($u, json_encode([
                        "op" => $data["op"],
                        "d" => [
                            "text" => $text,
                            "from" => $user->login,
                            "time" => date("d.m.Y H:i:s", time()),
                            "me" => $u == $user,
                        ],
                    ]));
                }

                return;
            }
            default: {
                $this->disconnect($user->socket, true, 4000);
                return;
            }
        }
    }

    protected function connected ($user) {
        // Do nothing: This is just an echo server, there's no need to track the user.
        // However, if we did care about the users, we would probably have a cookie to
        // parse at this step, would be looking them up in permanent storage, etc.
    }

    protected function closed ($user) {
        $idx = array_search($user, $this->charUsers);
        if($idx === false)
            return;
        unset($this->charUsers[$idx]);
    }
}

$echo = new echoServer("0.0.0.0","8089", 1024 * 32);

try {
    $echo->run();
}
catch (Exception $e) {
    $echo->stdout($e->getMessage());
}