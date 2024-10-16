<?php
session_start();

// ตรวจสอบว่ามีข้อมูลใน session หรือไม่
if (isset($_SESSION['card_data'])) {
    $card_data = $_SESSION['card_data'];
} else {
    echo "ไม่พบข้อมูล.";
    exit;
}

// ใช้ข้อมูลที่เก็บใน session
$card_id = $card_data['card_id'];
$distance = $card_data['distance'];
$user_license_plate = $card_data['user_license_plate'];
$parking_slot = $card_data['parking_slot'];

// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost"; // ชื่อเซิร์ฟเวอร์
$username = "root"; // ชื่อผู้ใช้
$password = ""; // รหัสผ่าน
$dbname = "carlot"; // ชื่อฐานข้อมูล

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ตรวจสอบ lot_id ที่เกี่ยวข้องกับ card_id
$selectLotIdQuery = "SELECT lot_id FROM card WHERE card_id = ?";
$stmt = $conn->prepare($selectLotIdQuery);
$stmt->bind_param('s', $card_id);
$stmt->execute();
$stmt->bind_result($lot_id);
$stmt->fetch();
$stmt->close();

// ตรวจสอบ bay_id และดึง bay_name ที่เกี่ยวข้องกับ lot_id
if ($lot_id) {
    $selectBayNameQuery = "
        SELECT bay.bay_name 
        FROM lot 
        INNER JOIN bay ON lot.bay_id = bay.bay_id 
        WHERE lot.lot_id = ?";
    $stmt = $conn->prepare($selectBayNameQuery);
    $stmt->bind_param('s', $lot_id);
    $stmt->execute();
    $stmt->bind_result($bay_name);
    $stmt->fetch();
    $stmt->close();
} else {
    $bay_name = "ไม่พบข้อมูล Bay";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ถ้ากดปุ่ม Yes
    if (isset($_POST['confirm'])) {
        $conn->begin_transaction();
        try {
            // อัพเดทสถานะในตาราง card
            $updateCardQuery = "UPDATE card SET status_id = 7 WHERE card_id = ?";
            $stmt = $conn->prepare($updateCardQuery);
            $stmt->bind_param('s', $card_id);
            $stmt->execute();

            // อัพเดทสถานะในตาราง lot
            if ($lot_id) {
                $updateLotQuery = "UPDATE lot SET status_id = 7 WHERE lot_id = ?";
                $stmt = $conn->prepare($updateLotQuery);
                $stmt->bind_param('s', $lot_id);
                $stmt->execute();
            }

            // Commit transaction
            $conn->commit();
            $message = 'จอดรถเรียบร้อยแล้ว';
            
            header("Location: parking_again.php");
            exit;
        } catch (Exception $e) {
            // Rollback transaction in case of error
            $conn->rollback();
            $message = 'เกิดข้อผิดพลาดในการจอดรถ';
        }
    }

    // ถ้ากดปุ่ม No
    if (isset($_POST['cancel'])) {
        $conn->begin_transaction();
        try {
            // อัพเดทสถานะในตาราง card
            $updateCardQuery = "UPDATE card SET status_id = 1 WHERE card_id = ?";
            $stmt = $conn->prepare($updateCardQuery);
            $stmt->bind_param('s', $card_id);
            $stmt->execute();

            // อัพเดทสถานะในตาราง lot
            if ($lot_id) {
                $updateLotQuery = "UPDATE lot SET status_id = 3 WHERE lot_id = ?";
                $stmt = $conn->prepare($updateLotQuery);
                $stmt->bind_param('s', $lot_id);
                $stmt->execute();
            }

            // Commit transaction
            $conn->commit();
            $message = 'ช่องจอดไม่สามารถใช้งานได้';
      
            header("Location: parking_again.php");
            exit;
        } catch (Exception $e) {
            // Rollback transaction in case of error
            $conn->rollback();
            $message = 'เกิดข้อผิดพลาดในการยกเลิกการจอดรถ';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลการจอดรถ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@300;400;500;600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #000428, #004e92);
            font-family: 'Teko', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #fff;
            font-family: 'Roboto', sans-serif;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            text-align: center;
            width: 100%;
            max-width: 400px;
            transition: transform 0.3s ease;
            font-size: 1.5rem;
        }

        .card:hover {
            transform: scale(1.05);
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            color: #e63946;
            text-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
        }

        p {
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .btn-group button {
            background-color: transparent;
            color: #e63946;
            border: 2px solid #e63946;
            padding: 10px 20px;
            border-radius: 8px;
            margin: 0 10px;
            cursor: pointer;
            font-size: 1.2rem;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
        }

        .btn-group button:hover {
            background-color: #e63946;
            color: #fff;
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(230, 57, 70, 0.8);
        }

        .btn-group button:active {
            transform: scale(1);
        }

        .message {
            margin-top: 1rem;
            color: #ffb703;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>ยืนยันการจอดรถ 🚗</h1>
        <p><strong>Card ID:</strong> <?= htmlspecialchars($card_id) ?></p>
        <p><strong>ทะเบียน:</strong> <?= htmlspecialchars($user_license_plate) ?></p>
        <p><strong>ความสูง:</strong> <?= htmlspecialchars($distance) ?> ซม.</p>
        <p><strong>ช่องจอด:</strong> <?= htmlspecialchars($parking_slot) ?></p>
        <p><strong>Bay:</strong> <?= htmlspecialchars($bay_name) ?></p>

        <form method="post">
            <p>ช่องจอดทำงาน ?</p>
            <div class="btn-group">
                <button type="submit" name="confirm">Yes, ทำงาน</button>
                <button type="submit" name="cancel">No, ไม่ทำงาน</button>
            </div>
        </form>

        <?php if (isset($message)): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
