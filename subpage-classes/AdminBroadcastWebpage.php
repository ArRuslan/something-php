<?php namespace WebpageClasses;
use DatabaseClass\Database;
require "AdminWebpage.php";
include "Database.php";
include "config.php";

class AdminBroadcastWebpage extends AdminWebpage {
    private string $title = "Broadcast";
    private string $header = "<h1>Header</h1>";
    private string $body = "<div class='body'>Body</div>";
    private string $footer;
    public Database $db;

    public function __construct(?string $title = null, ?string $header = null, ?string $body = null, ?string $footer = null) {
        global $FOOTER;
        if ($title != null) $this->title = $title;
        if ($header != null) $this->header = $header;
        if ($body != null) $this->body = $body;
        if ($footer != null)
            $this->footer = $footer;
        else
            $this->footer = $FOOTER;

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
        return file_get_contents("pages/adminPages/broadcastMessage.php");
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