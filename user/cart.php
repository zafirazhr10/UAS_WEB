<?php
session_start();
if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Hardcoded data handphone
$allPhones = [
    // iPhone
    ['merk'=>'iPhone','model'=>'17 Pro Max 1TB','gambar'=>'iphone17promax.jpg','storage'=>'1TB','tahun_rilis'=>2025,'harga'=>35000000,'stok'=>10],
    ['merk'=>'iPhone','model'=>'16 Pro','gambar'=>'iphone16pro.jpg','storage'=>'512GB','tahun_rilis'=>2024,'harga'=>25000000,'stok'=>15],
    ['merk'=>'iPhone','model'=>'15','gambar'=>'iphone15.jpg','storage'=>'256GB','tahun_rilis'=>2023,'harga'=>18000000,'stok'=>20],
    ['merk'=>'iPhone','model'=>'14 Plus','gambar'=>'iphone14plus.png','storage'=>'128GB','tahun_rilis'=>2022,'harga'=>12000000,'stok'=>25],
    ['merk'=>'iPhone','model'=>'13','gambar'=>'iphone13.jpg','storage'=>'128GB','tahun_rilis'=>2021,'harga'=>10000000,'stok'=>30],
    ['merk'=>'iPhone','model'=>'12 Mini','gambar'=>'iphone12mini.jpg','storage'=>'64GB','tahun_rilis'=>2020,'harga'=>8000000,'stok'=>20],

    // Samsung
    ['merk'=>'Samsung','model'=>'Galaxy S25','gambar'=>'samsung_s25.jpg','storage'=>'512GB','tahun_rilis'=>2025,'harga'=>25000000,'stok'=>8],
    ['merk'=>'Samsung','model'=>'Galaxy Z Fold 6','gambar'=>'samsung_zfold6.jpg','storage'=>'512GB','tahun_rilis'=>2025,'harga'=>35000000,'stok'=>5],
    ['merk'=>'Samsung','model'=>'Galaxy S24','gambar'=>'samsung_s24.jpg','storage'=>'256GB','tahun_rilis'=>2024,'harga'=>20000000,'stok'=>12],
    ['merk'=>'Samsung','model'=>'Galaxy S23','gambar'=>'samsung_s23.jpg','storage'=>'256GB','tahun_rilis'=>2023,'harga'=>15000000,'stok'=>20],
    ['merk'=>'Samsung','model'=>'Galaxy A54','gambar'=>'samsung_a54.jpg','storage'=>'128GB','tahun_rilis'=>2023,'harga'=>7000000,'stok'=>25],

    // Xiaomi
    ['merk'=>'Xiaomi','model'=>'Redmi Note 13','gambar'=>'xiaomi_redmi13.jpg','storage'=>'256GB','tahun_rilis'=>2025,'harga'=>4500000,'stok'=>20],
    ['merk'=>'Xiaomi','model'=>'Mi 14','gambar'=>'xiaomi_mi14.jpg','storage'=>'512GB','tahun_rilis'=>2025,'harga'=>8000000,'stok'=>12],
    ['merk'=>'Xiaomi','model'=>'Redmi Note 12','gambar'=>'xiaomi_note12.png','storage'=>'128GB','tahun_rilis'=>2024,'harga'=>3500000,'stok'=>25],
    ['merk'=>'Xiaomi','model'=>'Mi 13','gambar'=>'xiaomi_mi13.jpg','storage'=>'256GB','tahun_rilis'=>2023,'harga'=>7000000,'stok'=>18],
    ['merk'=>'Xiaomi','model'=>'Redmi 12C','gambar'=>'xiaomi_redmi12c.jpg','storage'=>'64GB','tahun_rilis'=>2022,'harga'=>2500000,'stok'=>30],

    // OPPO
    ['merk'=>'OPPO','model'=>'Reno 10','gambar'=>'oppo_reno10.jpg','storage'=>'256GB','tahun_rilis'=>2025,'harga'=>5500000,'stok'=>15],
    ['merk'=>'OPPO','model'=>'Find X7','gambar'=>'oppo_findx7.jpg','storage'=>'512GB','tahun_rilis'=>2025,'harga'=>9500000,'stok'=>8],
    ['merk'=>'OPPO','model'=>'Reno 9','gambar'=>'oppo_reno9.jpg','storage'=>'256GB','tahun_rilis'=>2024,'harga'=>5000000,'stok'=>12],
    ['merk'=>'OPPO','model'=>'A77','gambar'=>'oppo_a77.jpg','storage'=>'128GB','tahun_rilis'=>2023,'harga'=>3000000,'stok'=>20],

    // Vivo
    ['merk'=>'Vivo','model'=>'V50','gambar'=>'vivo_v50.jpg','storage'=>'128GB','tahun_rilis'=>2025,'harga'=>2500000,'stok'=>25],
    ['merk'=>'Vivo','model'=>'X100','gambar'=>'vivo_x100.jpg','storage'=>'512GB','tahun_rilis'=>2025,'harga'=>8000000,'stok'=>10],
    ['merk'=>'Vivo','model'=>'Y33s','gambar'=>'vivo_y33s.jpg','storage'=>'128GB','tahun_rilis'=>2023,'harga'=>2500000,'stok'=>30],
    ['merk'=>'Vivo','model'=>'V27','gambar'=>'vivo_v27.jpg','storage'=>'256GB','tahun_rilis'=>2024,'harga'=>4000000,'stok'=>18],
];


// Remove item
if(isset($_POST['remove'])){
    unset($_SESSION['cart'][$_POST['remove']]);
    header("Location: cart.php");
    exit;
}

// Update qty via input atau tombol +/-
if(isset($_POST['update'])){
    foreach($_POST['qty'] as $id=>$q){
        if($q>0) $_SESSION['cart'][$id] = $q;
        else unset($_SESSION['cart'][$id]);
    }
    header("Location: cart.php");
    exit;
}

// Add / minus qty
if(isset($_GET['add'])){
    $id = $_GET['add'];
    if(isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id]++;
    else $_SESSION['cart'][$id] = 1;
    header("Location: cart.php");
    exit;
}
if(isset($_GET['minus'])){
    $id = $_GET['minus'];
    if(isset($_SESSION['cart'][$id])){
        $_SESSION['cart'][$id]--;
        if($_SESSION['cart'][$id]<1) unset($_SESSION['cart'][$id]);
    }
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Keranjang - Handphone Gen Z</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background: linear-gradient(135deg, #0d1f4f 0%, #ffffff 100%); font-family:Poppins,sans-serif;}
img.phone-img{height:50px; object-fit:contain;}
.btn-sm{padding:0.25rem 0.5rem; font-size:0.8rem;}
.qty-btn{width:28px; height:28px; padding:0;}
</style>
</head>
<body>
<div class="container mt-3">
<h4>Keranjang Belanja</h4>
<a href="dashboard.php" class="btn btn-secondary w-100 mt-2">Kembali</a>

<?php if(empty($_SESSION['cart'])): ?>
<p>Keranjang kosong.</p>
<a href="dashboard.php" class="btn btn-primary btn-sm">Kembali Belanja</a>
<?php else: ?>
<form method="POST" action="checkout.php">
<table class="table table-sm table-bordered align-middle text-center">
<thead>
<tr>
<th>Pilih</th>
<th>Gambar</th>
<th>Merk & Model</th>
<th>Harga</th>
<th>Qty</th>
<th>Subtotal</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>
<?php foreach($_SESSION['cart'] as $id=>$qty):
    $item = $allPhones[$id];
    $subtotal = $item['harga'] * $qty;
?>

<tr>
<td><input type="checkbox" name="selected[]" value="<?= $id ?>" checked></td>
<td><img src="/toko_hp/assets/images/<?= $item['gambar'] ?>" class="phone-img"></td>
<td><?= $item['merk'].' '.$item['model'] ?></td>
<td>Rp <?= number_format($item['harga'],0,",",".") ?></td>
<td>
<div class="d-flex justify-content-center align-items-center">
    <a href="?minus=<?= $id ?>" class="btn btn-secondary btn-sm qty-btn">-</a>
    <input type="number" name="qty[<?= $id ?>]" value="<?= $qty ?>" min="1" class="form-control form-control-sm mx-1" style="width:50px;">
    <a href="?add=<?= $id ?>" class="btn btn-secondary btn-sm qty-btn">+</a>
</div>
</td>
<td>Rp <?= number_format($subtotal,0,",",".") ?></td>
<td>
    
<button type="submit" name="remove" value="<?= $id ?>" class="btn btn-danger btn-sm">Hapus</button>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<button type="submit" class="btn btn-success btn-sm">Checkout</button>
</form>
<?php endif; ?>
</div>
</body>
</html>
