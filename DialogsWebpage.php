<?php
require "BaseWebpage.php";
require "constants.php";

class DialogsWebpage implements BaseWebpage {

    private String $title = "Dialogs";
    private String $header = "<h1>Header</h1>";
    private String $body = "<div class='body'>Body</div>";
    private String $footer;

    public function __construct(?string $title = null, ?string $header = null, ?string $body = null, ?string $footer = null) {
        global $FOOTER;
        if($title != null) $this->title = $title;
        if($header != null) $this->header = $header;
        if($body != null) $this->body = $body;
        if($footer != null)
            $this->footer = $footer;
        else
            $this->footer = $FOOTER;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): DialogsWebpage {
        $this->title = $title;
        return $this;
    }

    public function getHeader(): string {
        return $this->header;
    }

    public function setHeader(string $header): DialogsWebpage {
        $this->header = $header;
        return $this;
    }

    public function getBody(): string {
        ob_start();
        include("pages/dialogs/body.php");
        return ob_get_clean();
    }

    public function setBody(string $body): DialogsWebpage {
        return $this;
    }

    public function getFooter(): string {
        return $this->footer;
    }

    public function setFooter(string $footer): DialogsWebpage {
        $this->footer = $footer;
        return $this;
    }
}