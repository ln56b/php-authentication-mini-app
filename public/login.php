<?php
require '../vendor/autoload.php';

use App\Auth;

session_start();

if (!empty($_POST)) {
    $pdo = new PDO("sqlite:../data.sqlite", null, null, [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $auth = new Auth($pdo);
    $user = $auth->login($_POST['username'], $_POST['password']);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>
<body class="p-4">
<h1>Log In</h1>
<form action="" method="post">
    <div class="form-group">
        <input type="text" class="form-control" name="username" placeholder="login">
        <input type="password" class="form-control" name="password" placeholder="password">
        <button class="btn btn-primary">Log In</button>
    </div>
</form>
<?php dump($_SESSION) ?>
</body>
</html>