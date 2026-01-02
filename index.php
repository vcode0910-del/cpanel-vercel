<?php
// --- BAGIAN PENGATURAN (Hanya ini yang perlu Anda ubah) ---
$PANEL_URL = "https://panel.domainanda.com"; // Ganti dengan URL Panel Pterodactyl Anda
$API_KEY   = "ptla_xxxxxxxxxxxxxxxxxxxx";   // Ganti dengan API Key Application Anda
// ---------------------------------------------------------

$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $user  = $_POST['username'];

    $data = [
        "username" => $user,
        "email" => $email,
        "first_name" => $user,
        "last_name" => "User",
        "password" => $_POST['password']
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
        $message = "<div class='alert alert-success'>Akun berhasil dibuat! Silakan cek email atau login ke panel.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Gagal! Pastikan email/username belum terdaftar atau API Key benar.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Akun Panel</title>
    <!-- Memakai CSS Bootstrap agar tampilan bagus otomatis -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; padding-top: 50px; }
        .card { max-width: 400px; margin: auto; border-radius: 15px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow">
        <div class="card-body">
            <h4 class="card-title text-center mb-4">Buat Akun Panel</h4>
            
            <?php echo $message; ?>

            <form method="POST">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Contoh: userganteng" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="email@gmail.com" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Daftar Sekarang</button>
            </form>
        </div>
    </div>
    <p class="text-center mt-3 text-muted">Integrasi Pterodactyl API via cPanel</p>
</div>

</body>
</html>
