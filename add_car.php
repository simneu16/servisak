<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vin = $_POST['vin'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $image = null;

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        $image = $targetDir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    $stmt = $mysqli->prepare("INSERT INTO cars (user_id, vin, brand, model, year, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $_SESSION['user_id'], $vin, $brand, $model, $year, $image);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Pridať vozidlo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Pridať nové vozidlo</h2>
    <form method="post" class="mt-4" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">VIN číslo</label>
            <input type="text" name="vin" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Značka</label>
            <input type="text" name="brand" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Model</label>
            <input type="text" name="model" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Rok výroby</label>
            <input type="number" name="year" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Obrázok vozidla (voliteľné)</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Pridať</button>
        <a href="dashboard.php" class="btn btn-secondary">Späť</a>
    </form>
</div>
</body>
</html>