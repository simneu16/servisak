<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $car_id = $_POST['car_id'];
    $type = $_POST['service_type'];
    $date = $_POST['service_date'];
    $note = $_POST['note'];
    $price = $_POST['price'] ?? null;
    $repeat_freq = $_POST['repeat_freq'] ?? null;
    $repeat_count = isset($_POST['repeat_count']) ? (int)$_POST['repeat_count'] : 0;

    $dates = [$date]; // Start with the original date
    if ($repeat_freq && $repeat_count > 0) {
        for ($i = 1; $i <= $repeat_count; $i++) { // Generate repeated dates
            $next_date = new DateTime($date);
            if ($repeat_freq === 'weekly') {
                $next_date->modify("+{$i} week");
            } elseif ($repeat_freq === 'monthly') {
                $next_date->modify("+{$i} month");
            } elseif ($repeat_freq === 'yearly') {
                $next_date->modify("+{$i} year");
            }
            $dates[] = $next_date->format('Y-m-d'); // Append formatted date
        }
    }

    // Insert all dates into the database
    $stmt = $mysqli->prepare("INSERT INTO services (car_id, service_type, service_date, note) VALUES (?, ?, ?, ?)");
    foreach ($dates as $service_date) {
        $stmt->bind_param("isss", $car_id, $type, $service_date, $note);
        $stmt->execute();
    }

    header("Location: dashboard.php");
    exit();
}

$cars = $mysqli->query("SELECT * FROM cars WHERE user_id = $user_id");
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Pridať servis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Pridať servis</h2>
    <form method="post" action="add_service.php">
        <div class="mb-3">
            <label for="car_id" class="form-label">Vyberte vozidlo</label>
            <select name="car_id" id="car_id" class="form-select" required>
                <?php while ($car = $cars->fetch_assoc()): ?>
                    <option value="<?= $car['id'] ?>"><?= htmlspecialchars($car['brand']) ?> <?= htmlspecialchars($car['model']) ?> (<?= $car['year'] ?>, <?= $car['vin'] ?>)</option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="service_type" class="form-label">Typ servisu</label>
            <input type="text" name="service_type" id="service_type" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="service_date" class="form-label">Dátum servisu</label>
            <input type="date" name="service_date" id="service_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Približná cena</label>
            <input type="number" name="price" id="price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="note" class="form-label">Poznámka</label>
            <textarea name="note" id="note" class="form-control"></textarea>
        </div>

        <!-- Toggle button for recurrence -->
        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#repeatOptions">Opakovať</button>

        <!-- Repeat options -->
        <div class="collapse mt-3" id="repeatOptions">
            <div class="mb-3">
                <label class="form-label">Frekvencia opakovania</label>
                <select name="repeat_freq" class="form-select">
                    <option value="">-- vyber --</option>
                    <option value="weekly">Každý týždeň</option>
                    <option value="monthly">Každý mesiac</option>
                    <option value="yearly">Každý rok</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Počet opakovaní</label>
                <input type="number" name="repeat_count" class="form-control" min="1">
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Uložiť</button>
    </form>
</div>
</body>
</html>