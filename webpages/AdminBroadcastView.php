<?php
namespace IdkChat\Webpages;

use IdkChat\Database\BaseDatabaseAdapter;

require_once "AdminView.php";
require_once $GLOBALS["ROOT_DIR"]."/config.php";

include_once $GLOBALS["ROOT_DIR"]."/db/BaseDatabaseAdapter.php";
include_once $GLOBALS["DB_ADAPTER_PATH"];

class AdminBroadcastView extends AdminView {
    private string $title = "Broadcast";
    private string $header = "<h1>Header</h1>";
    private string $body = "<div class='body'>Body</div>";
    private string $footer = "";
    protected BaseDatabaseAdapter $db;

    public function __construct(?string $title = null, ?string $header = null, ?string $body = null, ?string $footer = null) {
        parent::__construct($title, $header, $body, $footer);
        $this->db = $GLOBALS["DB_ADAPTER_CLASS"]::getInstance();
        $this->db->connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_password"], $GLOBALS["db_database"]);
    }

    public function setTitle(string $title): AdminBroadcastView {
        $this->title = $title;
        return $this;
    }

    public function setHeader(string $header): AdminBroadcastView {
        $this->header = $header;
        return $this;
    }

    public function getBody(): string {
        ob_start();
        include_once $GLOBALS["ROOT_DIR"]."/pages/adminPages/broadcastMessage.php";
        return ob_get_clean();
    }

    public function setBody(string $body): AdminBroadcastView {
        return $this;
    }

    public function setFooter(string $footer): AdminBroadcastView {
        $this->footer = $footer;
        return $this;
    }

    public function getFooter(): string {
        return $this->footer;
    }
}