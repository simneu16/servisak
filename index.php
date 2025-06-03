<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Servisák – Úvod</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="text-center">
        <h1 class="mb-4">Vitaj v aplikácii Servisák!</h1>
        <p class="lead">Spravuj svoje vozidlá a servisné termíny jednoducho a prehľadne.</p>
        <a href="login.php" class="btn btn-primary m-2">Prihlásiť sa</a>
        <a href="register.php" class="btn btn-outline-primary m-2">Registrovať sa</a>
    </div>
</div>
</body>
</html>