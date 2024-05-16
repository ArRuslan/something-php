<?php namespace IdkChat\Webpages;

require_once "BaseView.php";
require_once $GLOBALS["ROOT_DIR"]."/constants.php";

class AuthView implements BaseView {

    private string $title = "Login";
    private string $header = "<h1>Header</h1>";
    private string $body = "<div class='body'>Body</div>";
    private string $footer;

    public function __construct(?string $title = null, ?string $header = null, ?string $body = null, ?string $footer = null) {
        if ($title != null) $this->title = $title;
        if ($header != null) $this->header = $header;
        if ($body != null) $this->body = $body;
        if ($footer != null)
            $this->footer = $footer;
        else
            $this->footer = $GLOBALS["FOOTER"];
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): AuthView {
        $this->title = $title;
        return $this;
    }

    public function getHeader(): string {
        return $this->header;
    }

    public function setHeader(string $header): AuthView {
        $this->header = $header;
        return $this;
    }

    public function getBody(): string {
        return file_get_contents("pages/auth/body.html");
    }

    public function setBody(string $body): AuthView {
        return $this;
    }

    public function getFooter(): string {
        return $this->footer;
    }

    public function setFooter(string $footer): AuthView {
        $this->footer = $footer;
        return $this;
    }
}