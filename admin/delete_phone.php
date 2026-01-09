<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role']!='admin'){
    header("Location: ../index.php");
    exit;
}

// SIMULASI HAPUS
header("Location: dashboard.php");
exit;
