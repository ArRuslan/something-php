<?php namespace IdkChat\Webpages;

require_once "BaseView.php";
require_once $GLOBALS["ROOT_DIR"]."/constants.php";

class HomeView implements BaseView {

    private string $title = "Home";
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

    public function setTitle(string $title): HomeView {
        $this->title = $title;
        return $this;
    }

    public function getHeader(): string {
        return $this->header;
    }

    public function setHeader(string $header): HomeView {
        $this->header = $header;
        return $this;
    }

    public function getBody(): string {
        return file_get_contents("pages/index/body.html");
    }

    public function setBody(string $body): HomeView {
        return $this;
    }

    public function getFooter(): string {
        return $this->footer;
    }

    public function setFooter(string $footer): HomeView {
        $this->footer = $footer;
        return $this;
    }
}