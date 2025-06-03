<!DOCTYPE html>
<html lang="sk">
<head>
  <meta charset="UTF-8">
  <title>Servisák</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">🚗 Servisák</a>
    <div class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="#">Moje autá</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Pridať servis</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Garáže</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Odhlásiť sa</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container mt-4">
  <h2 class="mb-4">Vitaj späť, vodič!</h2>
  <div class="row g-3">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">🚙 Moje auto</h5>
          <p class="card-text">Škoda Octavia, 2018 • 124 000 km</p>
          <a href="#" class="btn btn-outline-primary btn-sm">Zobraziť servisnú históriu</a>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow-sm border-warning">
        <div class="card-body">
          <h5 class="card-title text-warning">🛠️ Nadchádzajúci servis</h5>
          <p class="card-text">Výmena oleja – do 15 dní</p>
          <a href="#" class="btn btn-warning btn-sm">Zobraziť detail</a>
        </div>
      </div>
    </div>
  </div>
</div>

<footer class="text-center mt-5 py-4 bg-dark text-white">
  <small>&copy; 2025 Servisák – Tvoja digitálna knižka údržby</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>