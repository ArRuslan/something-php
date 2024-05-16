<?php
interface ThemeStrategy
{
    public function applyTheme();
    public function getTheme();
}

class LightThemeStrategy implements ThemeStrategy
{
    public function applyTheme()
    {
        echo '<link rel="stylesheet" type="text/css" href="../../assets/css/light-theme.css">';
    }
    public function getTheme() : string
    {
        return  "<link rel=\"stylesheet\" href=\"/assets/css/light-theme.css\"/>";
    }
}

class DarkThemeStrategy implements ThemeStrategy
{
    public function applyTheme()
    {
        echo '<link rel="stylesheet" type="text/css" href="dark-theme.css">';
    }
    public function getTheme() : string
    {
        return '<link rel="stylesheet" type="text/css" href="dark-theme.css">';
    }
}
