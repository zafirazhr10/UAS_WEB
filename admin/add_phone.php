<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../index.php"); exit;
}

$error = '';

if(isset($_POST['submit'])){
    $merk    = $_POST['merk'];
    $model   = $_POST['model'];
    $storage = $_POST['storage'];
    $tahun   = $_POST['tahun_rilis'];
    $harga   = $_POST['harga'];
    $stok    = $_POST['stok'];

    // Upload gambar
    $gambar = 'default.jpg';
    if(!empty($_FILES['gambar']['name'])){
        $gambar = time().'_'.$_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], __DIR__.'/../assets/images/'.$gambar);
    }

    // Simpan ke session
    $_SESSION['phones'][] = [
        'id' => uniqid(),
        'merk' => $merk,
        'model' => $model,
        'storage' => $storage,
        'tahun_rilis' => $tahun,
        'harga' => $harga,
        'stok' => $stok,
        'gambar' => $gambar
    ];

    header("Location: dashboard.php"); exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Handphone</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#121212;color:#fff;font-family:Poppins,sans-serif;}
.card{background:#f8f9fa;color:#000;border-radius:15px;padding:20px;}
.btn-purple{background:#0d6efd;color:#fff;}
.btn-purple:hover{background:#0056b3;}
</style>
</head>
<body>
<div class="container mt-5">
<div class="card">
<h3>Tambah Handphone</h3>
<?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
<form method="POST" enctype="multipart/form-data">
<input class="form-control mb-2" type="text" name="merk" placeholder="Merk" required>
<input class="form-control mb-2" type="text" name="model" placeholder="Model" required>
<input class="form-control mb-2" type="text" name="storage" placeholder="Storage (ex: 128GB)" required>
<input class="form-control mb-2" type="number" name="tahun_rilis" placeholder="Tahun Rilis" required>
<input class="form-control mb-2" type="number" name="harga" placeholder="Harga" required>
<input class="form-control mb-2" type="number" name="stok" placeholder="Stok" required>
<input class="form-control mb-3" type="file" name="gambar">
<button name="submit" class="btn btn-purple w-100">Tambah</button>
<a href="dashboard.php" class="btn btn-secondary w-100 mt-2">Batal</a>
</form>
</div>
</div>
</body>
</html>
