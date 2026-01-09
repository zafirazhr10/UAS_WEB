<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../index.php"); exit;
}

$id = $_GET['id'] ?? '';
$phones = &$_SESSION['phones'];
$phone = null;

// Cari data berdasarkan id
foreach($phones as &$p){
    if($p['id'] === $id){
        $phone = &$p;
        break;
    }
}
if(!$phone){ echo "Data tidak ditemukan"; exit; }

if(isset($_POST['submit'])){
    $phone['merk'] = $_POST['merk'];
    $phone['model'] = $_POST['model'];
    $phone['storage'] = $_POST['storage'];
    $phone['tahun_rilis'] = $_POST['tahun_rilis'];
    $phone['harga'] = $_POST['harga'];
    $phone['stok'] = $_POST['stok'];

    // Upload gambar baru jika ada
    if(!empty($_FILES['gambar']['name'])){
        $newImage = time().'_'.$_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], __DIR__.'/../assets/images/'.$newImage);
        $phone['gambar'] = $newImage;
    }

    header("Location: dashboard.php"); exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Handphone</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:linear-gradient(135deg,#0d1f4f,#fff);font-family:Poppins,sans-serif;}
.card{border-radius:15px;padding:25px;margin-top:50px;}
</style>
</head>
<body>
<div class="container">
<div class="card shadow">
<h4 class="mb-3 text-primary">Edit Handphone</h4>
<form method="POST" enctype="multipart/form-data">
<input class="form-control mb-2" type="text" name="merk" value="<?= htmlspecialchars($phone['merk']) ?>" required>
<input class="form-control mb-2" type="text" name="model" value="<?= htmlspecialchars($phone['model']) ?>" required>
<input class="form-control mb-2" type="text" name="storage" value="<?= htmlspecialchars($phone['storage']) ?>" required>
<input class="form-control mb-2" type="number" name="tahun_rilis" value="<?= $phone['tahun_rilis'] ?>" required>
<input class="form-control mb-2" type="number" name="harga" value="<?= $phone['harga'] ?>" required>
<input class="form-control mb-2" type="number" name="stok" value="<?= $phone['stok'] ?>" required>
<img src="../assets/images/<?= $phone['gambar'] ?>" width="120" class="mb-2">
<input class="form-control mb-3" type="file" name="gambar">
<button name="submit" class="btn btn-success w-100">Simpan Perubahan</button>
<a href="dashboard.php" class="btn btn-secondary w-100 mt-2">Batal</a>
</form>
</div>
</div>
</body>
</html>
