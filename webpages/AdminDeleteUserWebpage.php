<?php

namespace IdkChat\Webpages;

use DOMDocument;
use IdkChat\Database\BaseDatabaseAdapter;

require_once "AdminWebpage.php";
require_once $GLOBALS["ROOT_DIR"]."/config.php";

include_once $GLOBALS["ROOT_DIR"]."/db/BaseDatabaseAdapter.php";
include_once $GLOBALS["DB_ADAPTER_PATH"];

class AdminDeleteUserWebpage extends AdminWebpage {
    private string $title = "Delete user";
    private string $header = "<h1>Header</h1>";
    private string $body = "<div class='body'>Body</div>";
    private string $footer = "";
    public BaseDatabaseAdapter $db;

    public function __construct(?string $title = null, ?string $header = null, ?string $body = null, ?string $footer = null) {
        parent::__construct($title, $header, $body, $footer);
        $this->db = $GLOBALS["DB_ADAPTER_CLASS"]::getInstance();
        $this->db->connect($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_password"], $GLOBALS["db_database"]);
    }

    public function setTitle(string $title): AdminDeleteUserWebpage {
        $this->title = $title;
        return $this;
    }

    public function setHeader(string $header): AdminDeleteUserWebpage {
        $this->header = $header;
        return $this;
    }

    public function getBody(): string {
        return (string)$this->fillTable()->saveHTML();
    }

    public function setBody(string $body): AdminDeleteUserWebpage {
        return $this;
    }

    public function setFooter(string $footer): AdminDeleteUserWebpage {
        $this->footer = $footer;
        return $this;
    }

    public function getFooter(): string {
        return $this->footer;
    }

    private function fillTable(): DOMDocument {
        ob_start();
        include_once $GLOBALS["ROOT_DIR"]."/pages/adminPages/deleteUser.php";
        $document = ob_get_clean();
        $dom = new DOMDocument();
        $dom->loadHTML($document);
        $tableBody = $dom->getElementById("table-body");
        $userArray = $this->db->getAllUsers();
        if ($userArray == null) {
            return $dom;
        }
        $userArrayLength = count($userArray);
        for ($index = 0; $index < $userArrayLength; $index++) {
            $tableRow = $dom->createElement('tr');
            $tableBody->appendChild($tableRow);
            $tableRowHead = $dom->createElement('th', (string)($index + 1));
            $tableRowHead->setAttribute("scope", "row");
            $tableLoginColumn = $dom->createElement('td', $userArray[$index][0]);
            $tableRow->appendChild($tableRowHead);
            $tableRow->appendChild($tableLoginColumn);
        }

        return $dom;
    }
}