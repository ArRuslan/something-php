<?php

session_start();

if(!isset($_POST["id"]) || !isset($_POST["count"])) {
    header("Location: /lb1");
    die;
}

include "constants.php";
global $PRODUCTS;

if($_POST["id"] >= count($PRODUCTS) || $_POST["id"] < 0 || $_POST["count"] < 0) {
    header("Location: /lb1");
    die;
}

if(!isset($_SESSION["cart"]))
    $_SESSION["cart"] = array();

array_push($_SESSION["cart"], array(
    "id" => $_POST["id"],
    "count" => $_POST["count"],
));

header("Location: /lb1");
