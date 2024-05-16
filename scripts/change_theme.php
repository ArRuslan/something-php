<?php
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if($_SESSION['theme'] === 'dark')
            {
                echo "set bright";
                $_SESSION['theme'] = 'light';
            }
            else
            {
                echo "set dark";
                $_SESSION['theme'] = 'dark';
            }
        }
        $str = $_SESSION['currentPage'];
        echo $str;
        header('Location:' ."/settings");
?>