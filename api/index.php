<?php
// --- PENGATURAN API ---
$PANEL_URL = "https://duhaistore.servercloud.my.id"; // PASTIKAN PAKAI HTTPS DAN TANPA TANDA / DI AKHIR
$API_KEY   = "ptla_PCjOecFOB2TdbS47qhJCTMxtkOMrf07zJHno4Bl3UwD";   // GUNAKAN APPLICATION API KEY
// ----------------------

$message = "";
$debug_info = "";

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
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $API_KEY",
        "Accept: application/json",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($httpCode == 201) {
        $message = "<div class='alert alert-success'>✅ Akun berhasil dibuat!</div>";
    } else {
        $message = "<div class='alert alert-danger'>❌ Gagal! Lihat detail error di bawah kotak ini.</div>";
        // Simpan info debug untuk ditampilkan di bawah form
        $debug_info = [
            "HTTP_CODE" => $httpCode,
            "CURL_ERROR" => $curlError,
            "RAW_RESPONSE" => json_decode($response, true) ?: $response
        ];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Debug Register Pterodactyl</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #1a1c23; color: white; padding-top: 50px; }</style>
</head>
<body>
    <div class="container" style="max-width: 500px;">
        <div class="card bg-dark border-secondary text-white p-4">
            <h4 class="text-center">Register Test</h4>
            <?php echo $message; ?>
            <form method="POST">
                <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
                <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
                <button type="submit" class="btn btn-primary w-100">Coba Daftar</button>
            </form>
        </div>

        <?php if ($debug_info): ?>
        <div class="mt-4 p-3 bg-black text-warning border border-warning shadow" style="font-family: monospace; font-size: 12px; overflow-x: auto;">
            <h5>DEBUG INFO (PENTING):</h5>
            <pre><?php print_r($debug_info); ?></pre>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
