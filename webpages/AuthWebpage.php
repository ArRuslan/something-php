<?php namespace Webpages;

require "BaseWebpage.php";
require "constants.php";

class AuthWebpage implements BaseWebpage {

    private string $title = "Login";
    private string $header = "<h1>Header</h1>";
    private string $body = "<div class='body'>Body</div>";
    private string $footer;

    public function __construct(?string $title = null, ?string $header = null, ?string $body = null, ?string $footer = null) {
        global $FOOTER;
        if ($title != null) $this->title = $title;
        if ($header != null) $this->header = $header;
        if ($body != null) $this->body = $body;
        if ($footer != null)
            $this->footer = $footer;
        else
            $this->footer = $FOOTER;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): AuthWebpage {
        $this->title = $title;
        return $this;
    }

    public function getHeader(): string {
        return $this->header;
    }

    public function setHeader(string $header): AuthWebpage {
        $this->header = $header;
        return $this;
    }

    public function getBody(): string {
        return file_get_contents("pages/auth/body.html");
    }

    public function setBody(string $body): AuthWebpage {
        return $this;
    }

    public function getFooter(): string {
        return $this->footer;
    }

    public function setFooter(string $footer): AuthWebpage {
        $this->footer = $footer;
        return $this;
    }
}