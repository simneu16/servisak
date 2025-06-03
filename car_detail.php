<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$car_id = $_GET['id'];
$stmt = $mysqli->prepare("SELECT * FROM cars WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $car_id, $_SESSION['user_id']);
$stmt->execute();
$car = $stmt->get_result()->fetch_assoc();

if (!$car) {
    echo "Auto sa nenašlo.";
    exit();
}

$services = $mysqli->query("SELECT * FROM services WHERE car_id = $car_id ORDER BY service_date DESC");
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Detail vozidla</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2><?= htmlspecialchars($car['brand']) ?> <?= htmlspecialchars($car['model']) ?> (<?= $car['year'] ?>)</h2>
    <p><strong>VIN:</strong> <?= $car['vin'] ?></p>

    <h4>Servisná história</h4>
    <ul class="list-group">
        <?php while ($s = $services->fetch_assoc()): ?>
            <li class="list-group-item">
                <strong><?= $s['service_type'] ?></strong> – <?= $s['service_date'] ?><br>
                <?= nl2br(htmlspecialchars($s['note'])) ?>
            </li>
        <?php endwhile; ?>
    </ul>

    <a href="dashboard.php" class="btn btn-secondary mt-4">Späť na dashboard</a>
</div>
</body>
</html>