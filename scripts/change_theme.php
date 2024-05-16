<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_SESSION['theme'] === 'dark') {
        $_SESSION['theme'] = 'light';
    } else {
        $_SESSION['theme'] = 'dark';
    }
}

header('Location:' . "/settings");
