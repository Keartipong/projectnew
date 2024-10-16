<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carlot";

// เชื่อมต่อกับฐานข้อมูล
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// อัปเดตสถานะที่จอดรถ
foreach ($_POST as $key => $value) {
    if (strpos($key, 'status_') === 0) {
        $lot_id = str_replace('status_', '', $key);
        $status_id = intval($value);
        $sql = "UPDATE lot SET status_id = $status_id WHERE lot_id = $lot_id";
        $conn->query($sql);
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();

// ส่งผู้ใช้กลับไปที่หน้าแสดงข้อมูล
header("Location: lot.php");
exit();
