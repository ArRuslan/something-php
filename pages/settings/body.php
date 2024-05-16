<link rel="stylesheet" href="/assets/css/settings.css"/>

<div class="settings">
    <h1>Settings:</h1>
    <br>
    <h3>Current theme: <?php echo $_SESSION["theme"] ?? "dark" ?></h3>
    <form method="post" action="/api/theme/change-theme">
        <button type="submit" name="change_session">Switch Theme</button>
    </form>
</div>
