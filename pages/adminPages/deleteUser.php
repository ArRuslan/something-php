<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/admin.css"/>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css"/>
</head>
<body>
    <h2 style="text-align: center;">Delete user feature</h2>
    <div id="input-user-login-div">
        <form method="POST" action="/scripts/delete-user.php">
            <p>Input the login of a user you want to delete</p>
            <input type="text" id="input-user-login" placeholder="login..." required name="user_login">
            <button id="delete-submit" type="submit">Delete user</button>
        </form>
    </div>
    <div id="users-table">
    <h4>User list</h4>
        <table class="table table-dark">
            <thead class="thead-primary">
                <tr>
                    <th scope="col" id="index-column">#</th>
                    <th scope="col" id="login-column">Login</th>
                </tr>
            </thead>
            <tbody id="table-body">

            </tbody>
        </table>
    </div>
</body>
</html>