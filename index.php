<?php namespace IdkChat;

$GLOBALS["ROOT_DIR"] = dirname(__FILE__);

use IdkChat\Webpages\HomeWebpage;
use IdkChat\Webpages\AuthWebpage;
use IdkChat\Webpages\DialogsWebpage;
use IdkChat\Webpages\AdminWebpage;
use IdkChat\Webpages\AdminDeleteUserWebpage;
use IdkChat\Webpages\AdminBroadcastWebpage;
use IdkChat\Lib\WebpageRoute;
use IdkChat\Lib\ApiRoute;
use IdkChat\Lib\Router;

include_once "lib/WebpageRoute.php";
include_once "lib/ApiRoute.php";
include_once "lib/Router.php";

$router = Router::getInstance();
$router->get("/", new WebpageRoute(HomeWebpage::class));
$router->get("/index", new WebpageRoute(HomeWebpage::class));
$router->get("/auth", new WebpageRoute(AuthWebpage::class));
$router->get("/dialogs", new WebpageRoute(DialogsWebpage::class));
$router->get("/admin", new WebpageRoute(AdminWebpage::class));
$router->get("/admin/delete-user", new WebpageRoute(AdminDeleteUserWebpage::class));
$router->get("/admin/broadcast", new WebpageRoute(AdminBroadcastWebpage::class));

$router->post("/api/auth/login", new ApiRoute(function() {
    include_once $GLOBALS["ROOT_DIR"]."/scripts/login.php";
}));
$router->post("/api/auth/register", new ApiRoute(function() {
    include_once $GLOBALS["ROOT_DIR"]."/scripts/register.php";
}));
$router->get("/api/auth/logout", new ApiRoute(function() {
    include_once $GLOBALS["ROOT_DIR"]."/scripts/logout.php";
}));
$router->post("/api/admin/delete-user", new ApiRoute(function() {
    include_once $GLOBALS["ROOT_DIR"]."/scripts/delete-user.php";
}));
$router->get("/api/ws-token", new ApiRoute(function() {
    include_once $GLOBALS["ROOT_DIR"]."/scripts/ws-token.php";
}));

echo $router->doTheThing();