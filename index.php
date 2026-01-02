<?php
// --- PENGATURAN API ---
$PANEL_URL = "https://panel.domainanda.com"; // Ganti dengan URL Panel Anda
$API_KEY   = "ptla_xxxxxxxxxxxxxxxxxxxx";   // Ganti dengan API Key Application Anda
// ----------------------

$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $user  = $_POST['username'];
    $pass  = $_POST['password'];

    $data = [
        "username" => $user,
        "email" => $email,
        "first_name" => $user,
        "last_name" => "User",
        "password" => $pass
    ];

    $ch = curl_init("$PANEL_URL/api/application/users");
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
        $message = "<div class='alert alert-success'>Akun berhasil dibuat!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Gagal! Cek API Key atau data sudah terdaftar.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Akun Pterodactyl</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
    <div class="container mt-5">
        <div class="card bg-secondary text-white shadow" style="max-width: 450px; margin: auto;">
            <div class="card-body">
                <h3 class="text-center">Register Panel</h3>
                <hr>
                <?php echo $message; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Buat Akun Sekarang</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
