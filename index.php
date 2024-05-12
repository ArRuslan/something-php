<?php namespace IdkChat;

$GLOBALS["ROOT_DIR"] = dirname(__FILE__);

use IdkChat\Webpages\HomeView;
use IdkChat\Webpages\AuthView;
use IdkChat\Webpages\DialogsView;
use IdkChat\Webpages\AdminView;
use IdkChat\Webpages\AdminDeleteUserView;
use IdkChat\Webpages\AdminBroadcastView;
use IdkChat\Lib\WebpageRoute;
use IdkChat\Lib\ApiRoute;
use IdkChat\Lib\Router;

include_once "lib/WebpageRoute.php";
include_once "lib/ApiRoute.php";
include_once "lib/Router.php";

$router = Router::getInstance();
$router->get("/", new WebpageRoute(HomeView::class));
$router->get("/index", new WebpageRoute(HomeView::class));
$router->get("/auth", new WebpageRoute(AuthView::class));
$router->get("/dialogs", new WebpageRoute(DialogsView::class));
$router->get("/admin", new WebpageRoute(AdminView::class));
$router->get("/admin/delete-user", new WebpageRoute(AdminDeleteUserView::class));
$router->get("/admin/broadcast", new WebpageRoute(AdminBroadcastView::class));

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