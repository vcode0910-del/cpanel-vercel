<?php
// --- PENGATURAN API ---
$PANEL_URL = "https://duhaistore.servercloud.my.id/"; // Ganti dengan URL Panel Anda
$API_KEY   = "ptla_AaTVmBOOKxi5LMlQQUh2gOMlooFXxvAw7PApfEs4iyX";   // Ganti dengan API Key Application Anda
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
        $message = "<div class='alert alert-success'>✅ Akun berhasil dibuat! Silakan cek email/panel.</div>";
    } else {
        $message = "<div class='alert alert-danger'>❌ Gagal! Akun mungkin sudah ada atau API Key salah.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pterodactyl Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS agar tampilan keren di Vercel */
        body { 
            background: #1a1c23; 
            color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .card { 
            background: #242731; 
            border: 1px solid #323541; 
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        .form-control {
            background: #1a1c23;
            border: 1px solid #323541;
            color: white;
        }
        .form-control:focus {
            background: #1a1c23;
            color: white;
            border-color: #3b82f6;
            box-shadow: none;
        }
        .btn-primary {
            background: #3b82f6;
            border: none;
            padding: 10px;
            font-weight: bold;
        }
        .btn-primary:hover {
            background: #2563eb;
        }
        label { margin-bottom: 5px; font-size: 14px; color: #a0aec0; }
    </style>
</head>
<body>

    <div class="card shadow-lg">
        <h4 class="text-center mb-4">Register Panel</h4>
        
        <?php echo $message; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" placeholder="Contoh: user123" required>
            </div>
            <div class="mb-3">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Min. 8 Karakter" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-2">Buat Akun</button>
        </form>
        
        <p class="text-center mt-4" style="font-size: 12px; color: #718096;">
            &copy; 2024 Cloud Hosting Integration
        </p>
    </div>

</body>
</html>
