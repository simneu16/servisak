<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $mysqli->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $_POST['username']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hash);

    if ($stmt->num_rows == 1) {
        $stmt->fetch();
        if (password_verify($_POST['password'], $hash)) {
            $_SESSION['user_id'] = $user_id;
            if (!empty($_POST['remember'])) {
                setcookie('user_id', $user_id, time() + (86400 * 30), "/"); // platnosť 30 dní
            }
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Nesprávne heslo.";
        }
    } else {
        $error = "Používateľ neexistuje.";
    }
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Prihlásenie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Prihlásenie</h2>
    <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="post" class="mt-3">
        <div class="mb-3">
            <label for="username" class="form-label">Používateľské meno</label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Heslo</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label" for="remember">Zapamätať si ma</label>
        </div>
        <button type="submit" class="btn btn-primary">Prihlásiť sa</button>
        <a href="index.php" class="btn btn-link">Späť</a>
    </form>
</div>
</body>
</html>