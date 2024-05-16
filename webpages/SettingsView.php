<?php namespace IdkChat\Webpages;
require_once "BaseView.php";
require_once $GLOBALS["ROOT_DIR"]."/constants.php";

class SettingsView implements BaseView {

    private string $title = "Settings";
    private string $header = "<h1>Header</h1>";
    private string $body = "<div class='body'>Body</div>";
    private string $footer = "";

    public function __construct(?string $title = null) {
        if ($title != null) $this->title = $title;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): SettingsView {
        $this->title = $title;
        return $this;
    }

    public function getHeader(): string {
        return $this->header;
    }

    public function setHeader(string $header): SettingsView {
        $this->header = $header;
        return $this;
    }

    public function getBody(): string {
        ob_start();
        include_once("pages/settingsPages/settings.html");
        return ob_get_clean();
    }

    public function setBody(string $body): SettingsView {
        return $this;
    }

    public function getFooter(): string {
        return $this->footer;
    }

    public function setFooter(string $footer): SettingsView {
        $this->footer = $footer;
        return $this;
    }
}
?>