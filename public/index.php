<?php
require '../vendor/autoload.php';

use App\Auth;

$pdo = new PDO("sqlite:../data.sqlite", null, null, [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$users=$pdo->query('SELECT * FROM users')->fetchAll();
$auth = new App\Auth($pdo);
$user = $auth->user();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>
<body>
<h1>Access to pages</h1>

<?php if (isset($_GET['login'])): ?>
<div class="alert alert-success">You have been signed in</div>
<?php endif; ?>

<?php if ($user): ?>
<p>Welcome <?= $user->username ?></p>
<?php endif ?>

<ul>
    <li><a href="admin.php">Admin only</a></li>
    <li><a href="user.php">User only</a></li>
</ul>

<table class="table table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>Login</th>
        <th>Role</th>
    </tr>
    <tbody>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= $user['id'] ?></td>
        <td><?= $user['username'] ?></td>
        <td><?= $user['role'] ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
    </thead>
</table>
</body>
</html>