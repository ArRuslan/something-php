<?php namespace WebpageClasses;
use DatabaseClass\Database;
require "AdminWebpage.php";
include "Database.php";
include "config.php";

class AdminDeleteUserWebpage extends AdminWebpage {
    private string $title = "Delete user";
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

    private function fillTable(): \DOMDocument {
        $document = file_get_contents("pages/adminPages/deleteUser.php");
        $dom = new \DOMDocument();
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