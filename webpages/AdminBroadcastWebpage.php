<?php namespace IdkChat\Webpages;
use IdkChat\DatabaseClass\Database as Database;

require_once "AdminWebpage.php";
require_once $GLOBALS["ROOT_DIR"]."/Database.php";
require_once $GLOBALS["ROOT_DIR"]."/config.php";

class AdminBroadcastWebpage extends AdminWebpage {
    private string $title = "Broadcast";
    private string $header = "<h1>Header</h1>";
    private string $body = "<div class='body'>Body</div>";
    private string $footer = "";
    public Database $db;

    public function __construct(?string $title = null, ?string $header = null, ?string $body = null, ?string $footer = null) {
        parent::__construct($title, $header, $body, $footer);
        $this->db = new Database($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_password"], $GLOBALS["db_database"]);
    }

    public function setTitle(string $title): AdminBroadcastWebpage {
        $this->title = $title;
        return $this;
    }

    public function setHeader(string $header): AdminBroadcastWebpage {
        $this->header = $header;
        return $this;
    }

    public function getBody(): string {
        ob_start();
        include_once $GLOBALS["ROOT_DIR"]."/pages/adminPages/broadcastMessage.php";
        return ob_get_clean();
    }

    public function setBody(string $body): AdminBroadcastWebpage {
        return $this;
    }

    public function setFooter(string $footer): AdminBroadcastWebpage {
        $this->footer = $footer;
        return $this;
    }

    public function getFooter(): string {
        return $this->footer;
    }
}