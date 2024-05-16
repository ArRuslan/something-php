<?php namespace IdkChat\Webpages;

require_once "BaseView.php";
require_once $GLOBALS["ROOT_DIR"]."/constants.php";

class AdminView implements BaseView {
    private string $title = "AdminController";
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

    public function setTitle(string $title): AdminView {
        $this->title = $title;
        return $this;
    }

    public function getHeader(): string {
        return $this->header;
    }

    public function setHeader(string $header): AdminView {
        $this->header = $header;
        return $this;
    }

    public function getBody(): string {
        ob_start();
        include_once $GLOBALS["ROOT_DIR"]."/pages/adminPages/admin.php";
        return ob_get_clean();
    }

    public function setBody(string $body): AdminView {
        return $this;
    }

    public function getFooter(): string {
        return $this->footer;
    }

    public function setFooter(string $footer): AdminView {
        $this->footer = $footer;
        return $this;
    }
}