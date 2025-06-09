<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Handle deletion logic
if (isset($_GET['delete_car_id'])) {
    $car_id = (int)$_GET['delete_car_id'];

    // Verify the car belongs to the logged-in user
    $stmt = $mysqli->prepare("SELECT id FROM cars WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $car_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Delete associated services
        $stmt = $mysqli->prepare("DELETE FROM services WHERE car_id = ?");
        $stmt->bind_param("i", $car_id);
        $stmt->execute();

        // Delete the car
        $stmt = $mysqli->prepare("DELETE FROM cars WHERE id = ?");
        $stmt->bind_param("i", $car_id);
        $stmt->execute();
    }

    header("Location: dashboard.php");
    exit();
}

if (isset($_GET['delete_service_id'])) {
    $service_id = (int)$_GET['delete_service_id'];

    $stmt = $mysqli->prepare("SELECT s.id FROM services s JOIN cars c ON s.car_id = c.id WHERE s.id = ? AND c.user_id = ?");
    $stmt->bind_param("ii", $service_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Delete the service
        $stmt = $mysqli->prepare("DELETE FROM services WHERE id = ?");
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
    }

    header("Location: dashboard.php");
    exit();
}

// Fetch cars
$cars = $mysqli->query("SELECT * FROM cars WHERE user_id = $user_id");
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Moje vozidlá</h2>
    <a href="add_car.php" class="btn btn-success mb-3">+ Pridať vozidlo</a>
    <a href="add_service.php" class="btn btn-outline-secondary mb-3">+ Pridať servis</a>
    <a href="logout.php" class="btn btn-danger float-end">Odhlásiť sa</a>

    <?php while ($car = $cars->fetch_assoc()): ?>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span>
                        <?= htmlspecialchars($car['brand']) ?> <?= htmlspecialchars($car['model']) ?> (<?= $car['year'] ?>)
                    </span>
                    <a href="dashboard.php?delete_car_id=<?= $car['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Naozaj chcete vymazať toto vozidlo?')">Vymazať</a>
                </div>
            </div>
            <div class="card-body">
                <p><strong>VIN:</strong> <?= $car['vin'] ?></p>
                <?php if (!empty($car['image'])): ?>
                    <img src="<?= htmlspecialchars($car['image']) ?>" alt="Obrázok vozidla" class="img-thumbnail mb-3" style="max-width: 200px;">
                <?php endif; ?>
                <h6>Nadchádzajúce servisy:</h6>
                <ul class="list-group">
                    <?php
                    $car_id = $car['id'];
                    $today = date('Y-m-d');
                    $limit = date('Y-m-d', strtotime("+30 days"));
                    $stmt = $mysqli->prepare("SELECT * FROM services WHERE car_id = ? AND service_date BETWEEN ? AND ?");
                    $stmt->bind_param("iss", $car_id, $today, $limit);
                    $stmt->execute();
                    $services = $stmt->get_result();

                    if ($services->num_rows === 0) {
                        echo "<li class='list-group-item'>Žiadne blížiace sa servisy</li>";
                    } else {
                        while ($s = $services->fetch_assoc()) {
                            $service_date = new DateTime($s['service_date']);
                            $formatted_date = $service_date->format('d.m.Y');
                            $now = new DateTime();
                            $interval = $now->diff($service_date);
                            $days_left = (int)$interval->format('%r%a');

                            if ($days_left === 0) {
                                $countdown = "<span class='badge bg-warning text-dark'>Dnes</span>";
                            } elseif ($days_left === 1) {
                                $countdown = "<span class='badge bg-info text-dark'>Zajtra</span>";
                            } elseif ($days_left > 1) {
                                $countdown = "<span class='badge bg-success'>O $days_left dní</span>";
                            } elseif ($days_left === -1) {
                                $countdown = "<span class='badge bg-danger'>Včera</span>";
                            } else {
                                $countdown = "<span class='badge bg-danger'>Pred " . abs($days_left) . " dňami</span>";
                            }
                            echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                                    <div>
                                        <strong>{$s['service_type']}</strong> – {$formatted_date} {$countdown}<br> {$s['note']}
                                    </div>
                                    <a href='dashboard.php?delete_service_id={$s['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Naozaj chcete vymazať tento servis?\")'>Vymazať</a>
                                  </li>";
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html