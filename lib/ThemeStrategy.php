<?php
interface ThemeStrategy
{
    public function getTheme();
}

class LightThemeStrategy implements ThemeStrategy
{
    public function getTheme() : string
    {
        return  "<link rel=\"stylesheet\" href=\"/assets/css/light-theme.css\"/>";
    }
}

class DarkThemeStrategy implements ThemeStrategy
{
    public function getTheme() : string
    {
        return "<link rel=\"stylesheet\" href=\"/assets/css/dark-theme.css\"/>";
    }
}
