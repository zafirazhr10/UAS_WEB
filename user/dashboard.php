<?php
session_start();

// Jika belum login, redirect ke login
if(!isset($_SESSION['role'])){
    header("Location: index.php");
    exit;
}

// =======================
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

// =======================
// Search
$search = isset($_GET['search']) ? strtolower($_GET['search']) : '';
$filteredPhones = [];
foreach($allPhones as $phone){
    if(!$search || strpos(strtolower($phone['merk']), $search) !== false || strpos(strtolower($phone['model']), $search) !== false){
        $filteredPhones[] = $phone;
    }
}

// =======================
// Pagination
$limit = 6;
$totalData = count($filteredPhones);
$totalPage = ceil($totalData / $limit);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page-1)*$limit;
$phones = array_slice($filteredPhones, $start, $limit);

// =======================
// Cart session
if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Add to cart
if(isset($_GET['add'])){
    $id = $_GET['add'];
    if(isset($_SESSION['cart'][$id])){
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    header("Location: dashboard.php?page=$page&search=".urlencode($search));
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>User Dashboard - Handphone Gen Z</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background: linear-gradient(135deg, #0d1f4f 0%, #ffffff 100%); color:#000; font-family:Poppins,sans-serif;}
.navbar{background:#001f70;}
.navbar-brand{color:#0d6efd; font-weight:bold;}
.card{background:#ffffff;color:#000;border-radius:15px;transition:0.3s;}
.card:hover{transform:translateY(-5px);box-shadow:0 10px 20px rgba(0,0,0,0.3);}
.btn-purple{background:#0d6efd;color:#fff;}
.btn-purple:hover{background:#0056b3;}
.cart-icon{position: relative;}
.cart-count{position:absolute;top:-8px;right:-8px;background:red;color:white;font-size:12px;width:20px;height:20px;border-radius:50%;text-align:center;line-height:20px;}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg shadow-sm">
<div class="container-fluid">
<a class="navbar-brand" href="#">Handphone Gen Z - User</a>

<div class="ms-auto d-flex align-items-center">
<a href="cart.php" class="btn btn-light me-2 cart-icon">
    🛒 Cart
    <?php if(count($_SESSION['cart'])>0): ?>
        <span class="cart-count"><?= array_sum($_SESSION['cart']) ?></span>
    <?php endif; ?>
</a>
 <a href="../logout.php" class="btn btn-danger">Logout</a>
</div>
</div>
</nav>

<div class="container mt-4">
<!-- Search -->
<div class="mb-3">
<form method="GET" class="d-flex" action="dashboard.php">
<input type="text" name="search" class="form-control me-2" placeholder="Cari merk/model..." value="<?= htmlspecialchars($search) ?>">
<button class="btn btn-primary">Search</button>
</form>
</div>

<div class="row">
<?php foreach($phones as $index=>$row): ?>
<div class="col-md-4 mb-4">
<div class="card p-2">
<img src="/toko_hp/assets/images/<?= $row['gambar'] ?>" class="card-img-top" style="height:150px;object-fit:contain;">
<div class="card-body">
<h6 class="card-title"><?= $row['merk'].' '.$row['model'] ?></h6>
<p class="card-text">
Storage: <?= $row['storage'] ?><br>
Tahun: <?= $row['tahun_rilis'] ?><br>
Harga: Rp <?= number_format($row['harga'],0,",",".") ?><br>
Stok: <?= $row['stok'] ?>
</p>
<a href="?add=<?= $index ?>&page=<?= $page ?>&search=<?= urlencode($search) ?>" class="btn btn-purple w-100">Add to Cart</a>
</div>
</div>
</div>
<?php endforeach; ?>
</div>

<!-- Pagination -->
<nav>
<ul class="pagination justify-content-center">
<?php for($i=1;$i<=$totalPage;$i++): ?>
<li class="page-item <?= ($i==$page)?'active':'' ?>">
<a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
</li>
<?php endfor; ?>
</ul>
</nav>
</div>
</body>
</html>
