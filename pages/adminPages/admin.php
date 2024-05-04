<?php
if (!isset($_SESSION["login"])) {
    header("Location: /auth");
    die;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/admin.css"/>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css"/>
    <title>Admin</title>
</head>
<body>
    <div class="container-for-features">
        <div class="list-group">
            <a href="/admin/delete-user" class="list-group-item list-group-item-action list-group-item-primary">Delete user</a>
            <a href="/admin/broadcast" class="list-group-item list-group-item-action list-group-item-primary">Send message to all users</a>
        </div>
    </div>
</body>
</html>
