<?php
session_start();

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

switch ($path) {
    case "/":
    case "/index":
        require "HomeWebpage.php";
        $page = new HomeWebpage();
        $page->setBody(file_get_contents("pages/index/body.html"));
        break;
    case "/auth":
        require "AuthWebpage.php";
        $page = new AuthWebpage();
        $page->setBody(file_get_contents("pages/auth/body.html"));
        break;
    case "/dialogs":
        require "DialogsWebpage.php";
        $page = new DialogsWebpage();
        ob_start();
        include("pages/dialogs/body.php");
        $page->setBody(ob_get_clean());
        $page->setHeader("");
        $page->setFooter("");
        break;
    default:
        require "NotFoundWebpage.php";
        $page = new NotFoundWebpage();
        break;
}

?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page->getTitle() ?></title>
    <link rel="stylesheet" href="/assets/css/main.css"/>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="/assets/css/home.css"/>
</head>
<body>
    <header class="p-3 colorbg text-white">    
        <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
            <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
            </a>
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
            <li><button class="bdel"><a href="/index" class="nav-link px-2 text-secondary">IDKChat</a></button></li>
            <li><button class="bdel"><a href="/pages/dialogs/body.php" class="nav-link px-2 text-white">Dialogs</a></button></li>
            <li><button class="bdel" onclick="loadContent('faq')"><a href="#" class="nav-link px-2 text-white">FAQs</a></button></li>
            <li><button class="bdel" onclick="loadContent('about')"><a href="#" class="nav-link px-2 text-white">About</a></button></li>
            </ul>
            <div class="text-end">
            <a href="/login.php">
            <button type="button" class="btn btn-outline-light me-2" onclick="deleteExceptHeader()">Login</button>
            </a>
            <button type="button" class="btn btn-warning">Sign-up</button>
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
<?php
echo $page->getBody();
echo $page->getFooter();
?>
</body>
</html>