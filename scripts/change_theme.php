<?php
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if($GLOBALS['theme'] === 'dark')
            {
                $GLOBALS['theme'] = 'light';
            }
            else
            {
                $GLOBALS['theme'] = 'dark';
            }
        }
?>