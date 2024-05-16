<?php namespace IdkChat\Webpages;

require_once "BaseView.php";
require_once $GLOBALS["ROOT_DIR"]."/constants.php";

class NotFoundView implements BaseView {

    private string $title = "Not found";
    private string $header = "<h1>Not found</h1>";
    private string $body = "<div class='body'>You are trying to access resource that does not exist!</div>";
    private string $footer;

    function __construct() {
        $this->footer = $GLOBALS["FOOTER"];
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): NotFoundView {
        $this->title = $title;
        return $this;
    }

    public function getHeader(): string {
        return $this->header;
    }

    public function setHeader(string $header): NotFoundView {
        $this->header = $header;
        return $this;
    }

    public function getBody(): string {
        return $this->body;
    }

    public function setBody(string $body): NotFoundView {
        $this->body = $body;
        return $this;
    }

    public function getFooter(): string {
        return $this->footer;
    }

    public function setFooter(string $footer): NotFoundView {
        $this->footer = $footer;
        return $this;
    }
}