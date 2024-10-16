<?php
// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carlot";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลจากฐานข้อมูล
$sql = "SELECT number, status_id, bay_id FROM lot";
$result = $conn->query($sql);

// ตรวจสอบว่ามีข้อมูลหรือไม่
if (!$result) {
    die("Error retrieving data: " . $conn->error);
}

// ส่งข้อมูลในรูปแบบ JSON
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);

$conn->close();
?>
