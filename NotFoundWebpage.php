<?php namespace WebpageClasses;

require "BaseWebpage.php";
require "constants.php";

class NotFoundWebpage implements BaseWebpage {

    private String $title = "Not found";
    private String $header = "<h1>Not found</h1>";
    private String $body = "<div class='body'>You are trying to access resource that does not exist!</div>";
    private String $footer;

    function __construct() {
        global $FOOTER;
        $this->footer = $FOOTER;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): NotFoundWebpage {
        $this->title = $title;
        return $this;
    }

    public function getHeader(): string {
        return $this->header;
    }

    public function setHeader(string $header): NotFoundWebpage {
        $this->header = $header;
        return $this;
    }

    public function getBody(): string {
        return $this->body;
    }

    public function setBody(string $body): NotFoundWebpage {
        $this->body = $body;
        return $this;
    }

    public function getFooter(): string {
        return $this->footer;
    }

    public function setFooter(string $footer): NotFoundWebpage {
        $this->footer = $footer;
        return $this;
    }
}