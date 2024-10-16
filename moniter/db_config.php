<?php
// db_config.php
$host = 'localhost';          // Hostname ของฐานข้อมูล
$username = 'root';           // ชื่อผู้ใช้ของฐานข้อมูล
$password = '';               // รหัสผ่านของฐานข้อมูล
$dbname = 'carlot';           // ชื่อฐานข้อมูล

try {
    // สร้างการเชื่อมต่อกับฐานข้อมูล
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // ตั้งค่าให้ PDO ขว้างข้อผิดพลาดหากเกิดข้อผิดพลาด
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // แสดงข้อผิดพลาดหากการเชื่อมต่อล้มเหลว
    echo 'Connection failed: ' . $e->getMessage();
}
?>
