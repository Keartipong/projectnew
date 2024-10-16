<?php
// get_latest_parking_info.php
include 'db_config.php';

header('Content-Type: application/json');

function getLatestParkingInfo() {
    global $pdo;
    $sql = "
        SELECT card.user_license_plate, lot.parked_zone, lot.number 
        FROM lot 
        INNER JOIN card ON lot.lot_id = card.lot_id 
        WHERE card.status_id = '7' 
        LIMIT 1
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$result = getLatestParkingInfo();
echo json_encode($result);
?>
