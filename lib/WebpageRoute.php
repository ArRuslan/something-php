<?php

namespace IdkChat\Lib;

include_once "BaseRoute.php";
require "ThemeStrategy.php";
class WebpageRoute implements BaseRoute {
    private string $pageCls;
    public $themeStrategy;

    public function __construct(string $pageCls) {
        $this->pageCls = $pageCls;
    }

    public function response(): string {
        session_start();
        if(!isset($_SESSION["theme"])) //If the theme is not set yet then we set it to light
        {
            $_SESSION["theme"] = "light";
        }

        $this->themeStrategy = isset($_SESSION['theme']) && $_SESSION['theme'] === 'light' ? new \LightThemeStrategy() : new \DarkThemeStrategy();
        $pageClass = explode("\\", $this->pageCls);
        $pageClass = $pageClass[count($pageClass)-1];

        include_once dirname(__FILE__) . "/../webpages/" . $pageClass . ".php";

        $page = new ("IdkChat\\Webpages\\".$pageClass)();
        $title = $page->getTitle();
        $body = $page->getBody();
        $footer = $page->getFooter();
        $headerStyle = $this->themeStrategy->getTheme();
        return "
            <html lang=\"en\">
            <head>
                <meta charset=\"utf-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
                <title>$title</title>
                <link rel=\"stylesheet\" href=\"/assets/css/main.css\"/>
                <link rel=\"stylesheet\" href=\"/assets/css/bootstrap.min.css\"/>
            </head>
            <body>
                <header class=\"p-3 colorbg text-white\">
                    <div class=\"container\">
                    <div class=\"d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start\">
                        <a href=\"/\" class=\"d-flex align-items-center mb-2 mb-lg-0 text-decoration-none\">
                        <svg class=\"bi me-2\" width=\"40\" height=\"32\" role=\"img\" aria-label=\"Bootstrap\"><use xlink:href=\"#bootstrap\"></use></svg>
                        </a>
                        <ul class=\"nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0\">
                        <li><button class=\"bdel\"><a id=\"homebutton\" href=\"/index\" class=\"nav-link px-2\">IDKChat</a></button></li>
                        <li><button class=\"bdel\"><a href=\"/dialogs\" class=\"nav-link px-2\">Dialogs</a></button></li>
                        <li><button class=\"bdel\" onclick=\"loadContent('faq')\"><a href=\"#\" class=\"nav-link px-2\">FAQs</a></button></li>
                        <li><button class=\"bdel\" onclick=\"loadContent('about')\"><a href=\"#\" class=\"nav-link px-2\">About</a></button></li>
                        <li><button class=\"bdel\"><a href=\"/admin\" class=\"nav-link px-2\">Admin</a></button></li>
                        </ul>
                        <div class=\"text-end\">
                        <a href=\"/auth\">
                            <button type=\"button\" class=\"btn btn-outline-light me-2\" id=\"loginButton\">Login</button>
                        </a>
                        <a href=\"/auth\">
                            <button type=\"button\" class=\"btn btn-warning\">Sign-up</button>
                        </a>
                        </div>
                    </div>
                    </div>
                </header>
            
                <script>
                    function loadContent(contentType){
                        switch(contentType){
                            case 'faq':
                                window.location.href='/index?faq=true';
                                break;
                            case 'about':
                                window.location.href='/index?about=true';
                                break;
                            default:
                            window.location.href='/index';
                        }
                    }
                </script>
                $body
                $footer
                $headerStyle
            </body>
            </html>
        ";
    }
}