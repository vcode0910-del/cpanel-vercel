<?php
session_start();

// --- PENGATURAN ADMIN (Ganti sesuka Anda) ---
$ADMIN_USER = "admin";
$ADMIN_PASS = "admin123";

// --- PENGATURAN PTERODACTYL API ---
$PANEL_URL = "https://duhaistore.servercloud.my.id/"; // Tanpa tanda / di akhir
$API_KEY   = "ptla_PCjOecFOB2TdbS47qhJCTMxtkOMrf07zJHno4Bl3UwD";   // Application API Key

// --- LOGIKA LOGOUT ---
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: /");
    exit;
}

// --- LOGIKA LOGIN ---
$error = "";
if (isset($_POST['login'])) {
    if ($_POST['user'] == $ADMIN_USER && $_POST['pass'] == $ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $error = "Username atau Password salah!";
    }
}

// --- LOGIKA BUAT SERVER ---
$message = "";
if (isset($_POST['create_server']) && isset($_SESSION['admin_logged_in'])) {
    // Data Server (Ini adalah pengaturan dasar)
    $data = [
        "name" => $_POST['server_name'],
        "user" => (int)$_POST['user_id'], // ID User di Pterodactyl
        "egg" => (int)$_POST['egg_id'],   // ID Egg (Contoh: Minecraft)
        "docker_image" => "ghcr.io/pterodactyl/yolks:java_17",
        "startup" => "java -Xms128M -Xmx{{SERVER_MEMORY}}M -jar {{SERVER_JARFILE}}",
        "limits" => [
            "memory" => (int)$_POST['ram'],
            "swap" => 0,
            "disk" => (int)$_POST['disk'],
            "io" => 500,
            "cpu" => (int)$_POST['cpu']
        ],
        "feature_limits" => ["databases" => 0, "allocations" => 1, "backups" => 0],
        "environment" => [
            "SERVER_JARFILE" => "server.jar",
            "VANILLA_VERSION" => "latest"
        ],
        "allocation" => ["default" => (int)$_POST['allocation_id']]
    ];

    $ch = curl_init("$PANEL_URL/api/application/servers");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $API_KEY",
        "Accept: application/json",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 201) {
        $message = "<div class='alert alert-success'>✅ Server Berhasil Dibuat!</div>";
    } else {
        $res = json_decode($response, true);
        $detail = $res['errors'][0]['detail'] ?? "Gagal membuat server.";
        $message = "<div class='alert alert-danger'>❌ Error: $detail</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel Integration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #111827; color: #f3f4f6; }
        .card { background: #1f2937; border: 1px solid #374151; color: white; }
        .form-control { background: #111827; border: 1px solid #374151; color: white; }
        .form-control:focus { background: #111827; color: white; border-color: #3b82f6; box-shadow: none; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh; padding: 20px;">

    <?php if (!isset($_SESSION['admin_logged_in'])): ?>
    <!-- FORM LOGIN -->
    <div class="card p-4 shadow" style="max-width: 400px; width: 100%;">
        <h3 class="text-center mb-4">Login Admin</h3>
        <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="user" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="pass" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Masuk</button>
        </form>
    </div>

    <?php else: ?>
    <!-- DASHBOARD BUAT SERVER -->
    <div class="card p-4 shadow" style="max-width: 600px; width: 100%;">
        <div class="d-flex justify-content-between mb-4">
            <h4>Dashboard Server</h4>
            <a href="?logout" class="btn btn-sm btn-danger">Logout</a>
        </div>
        
        <?php echo $message; ?>

        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Nama Server</label>
                    <input type="text" name="server_name" class="form-control" placeholder="My Server" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>User ID (Owner)</label>
                    <input type="number" name="user_id" class="form-control" placeholder="Contoh: 1" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>RAM (MB)</label>
                    <input type="number" name="ram" class="form-control" value="1024" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Disk (MB)</label>
                    <input type="number" name="disk" class="form-control" value="5000" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>CPU (%)</label>
                    <input type="number" name="cpu" class="form-control" value="100" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Egg ID</label>
                    <input type="number" name="egg_id" class="form-control" placeholder="ID Egg" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Allocation ID (Port)</label>
                    <input type="number" name="allocation_id" class="form-control" placeholder="ID Port" required>
                </div>
            </div>

            <button type="submit" name="create_server" class="btn btn-success w-100 mt-3">Buat Server Sekarang</button>
        </form>
    </div>
    <?php endif; ?>

</body>
</html>
