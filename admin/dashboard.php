<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php"); exit;
}

/* =====================================================
   DATA HARDCODE & MERGE DENGAN SESSION
===================================================== */
$hardcodedPhones = [
    ['id'=>uniqid(),'merk'=>'iPhone','model'=>'17 Pro Max 1TB','gambar'=>'iphone17promax.jpg','storage'=>'1TB','tahun_rilis'=>2025,'harga'=>35000000,'stok'=>10],
    ['id'=>uniqid(),'merk'=>'iPhone','model'=>'16 Pro','gambar'=>'iphone16pro.jpg','storage'=>'512GB','tahun_rilis'=>2024,'harga'=>25000000,'stok'=>15],
    ['id'=>uniqid(),'merk'=>'iPhone','model'=>'15','gambar'=>'iphone15.jpg','storage'=>'256GB','tahun_rilis'=>2023,'harga'=>18000000,'stok'=>20],
    ['id'=>uniqid(),'merk'=>'iPhone','model'=>'14 Plus','gambar'=>'iphone14plus.png','storage'=>'128GB','tahun_rilis'=>2022,'harga'=>12000000,'stok'=>25],
    ['id'=>uniqid(),'merk'=>'iPhone','model'=>'13','gambar'=>'iphone13.jpg','storage'=>'128GB','tahun_rilis'=>2021,'harga'=>10000000,'stok'=>30],
    ['id'=>uniqid(),'merk'=>'iPhone','model'=>'12 Mini','gambar'=>'iphone12mini.jpg','storage'=>'64GB','tahun_rilis'=>2020,'harga'=>8000000,'stok'=>20],
    // Samsung
    ['id'=>uniqid(),'merk'=>'Samsung','model'=>'Galaxy S25','gambar'=>'samsung_s25.jpg','storage'=>'512GB','tahun_rilis'=>2025,'harga'=>25000000,'stok'=>8],
    ['id'=>uniqid(),'merk'=>'Samsung','model'=>'Galaxy Z Fold 6','gambar'=>'samsung_zfold6.jpg','storage'=>'512GB','tahun_rilis'=>2025,'harga'=>35000000,'stok'=>5],
    ['id'=>uniqid(),'merk'=>'Samsung','model'=>'Galaxy S24','gambar'=>'samsung_s24.jpg','storage'=>'256GB','tahun_rilis'=>2024,'harga'=>20000000,'stok'=>12],
    ['id'=>uniqid(),'merk'=>'Samsung','model'=>'Galaxy S23','gambar'=>'samsung_s23.jpg','storage'=>'256GB','tahun_rilis'=>2023,'harga'=>15000000,'stok'=>20],
    ['id'=>uniqid(),'merk'=>'Samsung','model'=>'Galaxy A54','gambar'=>'samsung_a54.jpg','storage'=>'128GB','tahun_rilis'=>2023,'harga'=>7000000,'stok'=>25],
    // Xiaomi
    ['id'=>uniqid(),'merk'=>'Xiaomi','model'=>'Redmi Note 13','gambar'=>'xiaomi_redmi13.jpg','storage'=>'256GB','tahun_rilis'=>2025,'harga'=>4500000,'stok'=>20],
    ['id'=>uniqid(),'merk'=>'Xiaomi','model'=>'Mi 14','gambar'=>'xiaomi_mi14.jpg','storage'=>'512GB','tahun_rilis'=>2025,'harga'=>8000000,'stok'=>12],
    ['id'=>uniqid(),'merk'=>'Xiaomi','model'=>'Redmi Note 12','gambar'=>'xiaomi_note12.png','storage'=>'128GB','tahun_rilis'=>2024,'harga'=>3500000,'stok'=>25],
    ['id'=>uniqid(),'merk'=>'Xiaomi','model'=>'Mi 13','gambar'=>'xiaomi_mi13.jpg','storage'=>'256GB','tahun_rilis'=>2023,'harga'=>7000000,'stok'=>18],
    ['id'=>uniqid(),'merk'=>'Xiaomi','model'=>'Redmi 12C','gambar'=>'xiaomi_redmi12c.jpg','storage'=>'64GB','tahun_rilis'=>2022,'harga'=>2500000,'stok'=>30],
    // OPPO
    ['id'=>uniqid(),'merk'=>'OPPO','model'=>'Reno 10','gambar'=>'oppo_reno10.jpg','storage'=>'256GB','tahun_rilis'=>2025,'harga'=>5500000,'stok'=>15],
    ['id'=>uniqid(),'merk'=>'OPPO','model'=>'Find X7','gambar'=>'oppo_findx7.jpg','storage'=>'512GB','tahun_rilis'=>2025,'harga'=>9500000,'stok'=>8],
    ['id'=>uniqid(),'merk'=>'OPPO','model'=>'Reno 9','gambar'=>'oppo_reno9.jpg','storage'=>'256GB','tahun_rilis'=>2024,'harga'=>5000000,'stok'=>12],
    ['id'=>uniqid(),'merk'=>'OPPO','model'=>'A77','gambar'=>'oppo_a77.jpg','storage'=>'128GB','tahun_rilis'=>2023,'harga'=>3000000,'stok'=>20],
    // Vivo
    ['id'=>uniqid(),'merk'=>'Vivo','model'=>'V50','gambar'=>'vivo_v50.jpg','storage'=>'128GB','tahun_rilis'=>2025,'harga'=>2500000,'stok'=>25],
    ['id'=>uniqid(),'merk'=>'Vivo','model'=>'X100','gambar'=>'vivo_x100.jpg','storage'=>'512GB','tahun_rilis'=>2025,'harga'=>8000000,'stok'=>10],
    ['id'=>uniqid(),'merk'=>'Vivo','model'=>'Y33s','gambar'=>'vivo_y33s.jpg','storage'=>'128GB','tahun_rilis'=>2023,'harga'=>2500000,'stok'=>30],
    ['id'=>uniqid(),'merk'=>'Vivo','model'=>'V27','gambar'=>'vivo_v27.jpg','storage'=>'256GB','tahun_rilis'=>2024,'harga'=>4000000,'stok'=>18],
];

// Merge data hardcode & session lama
if (!isset($_SESSION['phones'])) {
    $_SESSION['phones'] = $hardcodedPhones;
} else {
    foreach ($hardcodedPhones as $h) {
        $exists = false;
        foreach ($_SESSION['phones'] as $s) {
            if ($s['merk']==$h['merk'] && $s['model']==$h['model'] && $s['storage']==$h['storage']) {
                $exists = true; break;
            }
        }
        if (!$exists) $_SESSION['phones'][] = $h;
    }
}

// ================= HAPUS HANDPHONE iPhone 11 yang stok 15 =================
foreach($_SESSION['phones'] as $k => $p){
    if($p['merk']=='iPhone' && $p['model']=='11' && $p['stok']==15){
        unset($_SESSION['phones'][$k]);
    }
}
$_SESSION['phones'] = array_values($_SESSION['phones']); // reset index

$phones = $_SESSION['phones'];

// ================= SEARCH =================
$search = strtolower($_GET['search'] ?? '');
if ($search) {
    $phones = array_filter($phones, function($p) use ($search){
        return strpos(strtolower($p['merk']), $search) !== false ||
               strpos(strtolower($p['model']), $search) !== false;
    });
}

// ================= PAGINATION PER MERK =================
$merkFilter = $_GET['merk'] ?? '';
$merks = array_unique(array_map(fn($p)=>$p['merk'],$phones));
sort($merks);

// filter merk untuk halaman
if ($merkFilter && in_array($merkFilter, $merks)) {
    $phones = array_filter($phones, fn($p)=>$p['merk']==$merkFilter);
}

// pagination
$limit = 6;
$totalData = count($phones);
$totalPage = ceil($totalData / $limit);
$page = $_GET['page'] ?? 1;
$page = max(1,$page);
$start = ($page-1)*$limit;
$phonesPage = array_slice($phones, $start, $limit);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:linear-gradient(135deg,#0d1f4f,#fff);font-family:Poppins,sans-serif;}
.navbar{background:#001f70;}
.card{border-radius:15px;transition:.3s}
.card:hover{transform:translateY(-5px);box-shadow:0 10px 20px rgba(0,0,0,.3)}
</style>
</head>
<body>

<nav class="navbar navbar-dark px-4">
<span class="navbar-brand">Admin Handphone Gen Z</span>
<div>
<a href="add_phone.php" class="btn btn-primary me-2">Tambah</a>
<a href="../logout.php" class="btn btn-danger">Logout</a>
</div>
</nav>

<div class="container mt-4">

<!-- Pilih merk -->
<form method="GET" class="d-flex mb-3">
<select name="merk" class="form-select me-2" onchange="this.form.submit()">
    <option value="">-- Pilih Merk --</option>
    <?php foreach($merks as $m): ?>
        <option value="<?= $m ?>" <?= ($merkFilter==$m)?'selected':'' ?>><?= $m ?></option>
    <?php endforeach; ?>
</select>
<input type="text" name="search" class="form-control me-2" placeholder="Cari model..." value="<?= htmlspecialchars($_GET['search']??'') ?>">
<button class="btn btn-primary">Cari</button>
</form>

<div class="row">
<?php foreach($phonesPage as $p): ?>
<div class="col-md-4 mb-4">
<div class="card p-2">
<img src="/toko_hp/assets/images/<?= $p['gambar'] ?>" style="height:150px;object-fit:contain">
<div class="card-body">
<h6><?= $p['merk'].' '.$p['model'] ?></h6>
<p>
Storage: <?= $p['storage'] ?><br>
Tahun: <?= $p['tahun_rilis'] ?><br>
Harga: Rp <?= number_format($p['harga'],0,",",".") ?><br>
Stok: <?= $p['stok'] ?>
</p>
<a href="edit_phone.php?id=<?= $p['id'] ?>" class="btn btn-success w-100 mb-1">Edit</a>
<a href="delete_phone.php?id=<?= $p['id'] ?>" onclick="return confirm('Hapus data?')" class="btn btn-danger w-100">Hapus</a>
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
<a class="page-link" href="?page=<?= $i ?>&merk=<?= $merkFilter ?>&search=<?= urlencode($_GET['search']??'') ?>"><?= $i ?></a>
</li>
<?php endfor; ?>
</ul>
</nav>

</div>
</body>
</html>
