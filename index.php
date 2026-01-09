<?php
session_start();
include "config.php";

/* =========================
   PROSES LOGIN (PALING ATAS)
========================= */
if (isset($_POST['login'])) {
    $email = $_POST['email'];

    $q = $conn->query("SELECT * FROM users WHERE email='$email' AND role='admin'");
    $admin = $q->fetch_assoc();

    if ($admin) {
        $_SESSION['role']  = 'admin';
        $_SESSION['email'] = $email;
        $_SESSION['nama']  = $admin['nama'];

        header("Location: /toko_hp/admin/dashboard.php");
        exit;
    } else {
        $_SESSION['role']  = 'user';
        $_SESSION['email'] = $email;
        $_SESSION['nama']  = 'User Gen Z';

        header("Location: /toko_hp/user/dashboard.php");
        exit;
    }
}

/* =========================
   ROUTING
========================= */
$url = isset($_GET['url']) ? trim($_GET['url'], '/') : '';

switch ($url) {

    case '':
        // LOGIN FORM (HTML DI BAWAH)
        break;

    case 'admin':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: /toko_hp");
            exit;
        }
        require __DIR__ . '/admin/dashboard.php';
        exit;

    case 'admin/add':
        require __DIR__ . '/admin/add_phone.php';
        exit;

    case 'admin/edit':
        require __DIR__ . '/admin/edit_phone.php';
        exit;

    case 'admin/delete':
        require __DIR__ . '/admin/delete_phone.php';
        exit;

    case 'user':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
            header("Location: /toko_hp");
            exit;
        }
        require __DIR__ . '/user/dashboard.php';
        exit;

    case 'user/cart':
        require __DIR__ . '/user/cart.php';
        exit;

    case 'user/checkout':
        require __DIR__ . '/user/checkout.php';
        exit;

    case 'logout':
        session_destroy();
        header("Location: /toko_hp");
        exit;

    default:
        http_response_code(404);
        echo "<h2 style='text-align:center;margin-top:50px'>404 - Page Not Found</h2>";
        exit;
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login | Handphone Gen Z</title>

<!-- BOOTSTRAP -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(135deg,#000,#0d6efd);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:Poppins,sans-serif;
}
.card{
    border-radius:16px;
    width:360px;
    background:#fff;
    box-shadow:0 15px 30px rgba(0,0,0,0.4);
}
.title{
    font-weight:bold;
    color:#0d6efd;
    text-align:center;
}
</style>
</head>
<body>

<div class="card p-4">
    <h3 class="title">Handphone Gen Z</h3>
    <p class="text-center text-muted">Login User & Admin</p>

    <form method="POST">
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email bebas" required>
        </div>
        <div class="mb-3">
            <input type="text" name="password" class="form-control" placeholder="Password bebas">
        </div>
        <button name="login" class="btn btn-primary w-100">Login</button>
    </form>

    <small class="text-muted text-center d-block mt-3">
        Admin harus email terdaftar<br>
        User bebas login
    </small>
</div>

</body>
</html>
