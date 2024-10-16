<?php
// db_config.php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'carlot';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// Get the latest record from the database
function getLatestParkingInfo() {
    global $pdo;
    $sql = "
        SELECT card.user_license_plate, lot.parked_zone, lot.number, lot.bay_id, bay.bay_name, card.card_id
        FROM card 
        INNER JOIN lot ON card.lot_id = lot.lot_id 
        INNER JOIN bay ON lot.bay_id = bay.bay_id  -- JOIN ตาราง bay
        WHERE lot.status_id = '6'  
        ORDER BY card.time DESC
        LIMIT 1
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get the latest 10 records, ordering from most recent to oldest
$stmt = $pdo->query("
    SELECT card.user_license_plate, lot.parked_zone, lot.number, lot.bay_id, bay.bay_name, card.card_id
    FROM card 
    INNER JOIN lot ON card.lot_id = lot.lot_id 
    INNER JOIN bay ON lot.bay_id = bay.bay_id  -- JOIN ตาราง bay
    WHERE lot.status_id IN (6, 7)  -- ใช้ IN เพื่อดึงค่า status_id ที่เป็น 6 หรือ 7
    ORDER BY card.time DESC 
    LIMIT 10
");
$cards = $stmt->fetchAll();

$result = getLatestParkingInfo();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latest Parking Info - Car Theme</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <style>
        body {
            font-family: 'Roboto Mono', monospace;
            background-image: url('1.jpg'); /* Use url() to specify the image path */
            background-size: cover; /* Ensure the image covers the entire background */
            background-position: center; /* Center the image */
            background-repeat: no-repeat; /* Prevent image from repeating */
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
            position: relative;
            color: #111; /* Text contrast */
        }

        h1 {
        background-clip: text;
        text-align: center;
        font-size: 3em;
        font-weight: 700;
        margin-bottom: 30px;
        letter-spacing: 2px;
        text-transform: uppercase;
        background: linear-gradient(90deg, #ff3e00, #00f7ff); /* Gradient color */
        -webkit-background-clip: text; /* Clip gradient to text */
        -webkit-text-fill-color: transparent; /* Required for Safari */
        color: transparent; /* Ensures text color is transparent to show the gradient */
        animation: colorShift 5s infinite;
      }

        @keyframes colorShift {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }

        .section-header {
            font-size: 2.5em;
            font-weight: 600;
            color: #000;
            margin: 20px 0;
            text-align: center;
            width: 85%;
            max-width: 1000px;
            background: linear-gradient(90deg, #ffffff, #111); /* Adds racing stripe effect */
            padding: 10px;
            border-radius: 8px;
            text-transform: uppercase;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1.5s ease-out;
        }

        .latest-info, table {
            width: 85%;
            max-width: 1000px;
            margin: 0 auto 20px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.95); /* Brighter background with white contrast */
            box-shadow: 0 0 20px rgba(255, 69, 0, 0.2), 0 0 40px rgba(255, 69, 0, 0.2); /* Stronger shadow effect */
            overflow: hidden;
            position: relative;
        }

        .latest-info {
            display: flex;
            justify-content: space-around;
            padding: 15px;
            border: 4px solid #ff3e00; /* Neon border */
            margin-bottom: 30px;
            background: rgba(0, 0, 0, 0.8);
            animation: slideIn 1s ease-out;
        }

        @keyframes slideIn {
            0% {
                transform: translateY(-50px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .info-box {
            flex: 1;
            margin: 0 10px;
            padding: 15px;
            background: rgba(213, 213, 213  , 0.95); /* White background for contrast */
            border: 2px solid #ff3e00;
            text-align: center;
            animation: glowBox 2s infinite alternate;
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .info-box:hover {
            transform: scale(1.05);
        }

        @keyframes glowBox {
            0% {
                box-shadow: 0 0 10px #ff3e00, 0 0 20px #ff3e00;
            }
            100% {
                box-shadow: 0 0 5px #ff3e00, 0 0 10px #ff3e00;
            }
        }

        .info-box h2 {
            font-size: 1.7em;
            color: #ff3e00; /* Text color for info box */
            margin-bottom: 5px;
            letter-spacing: 1px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
        }

        .info-box p {
            font-size: 1.4em;
            color: #111;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
            background-color: rgba(213, 213, 213  , 0.95); /* White contrast for table */
            animation: fadeIn 1.5s ease-out;
        }

        th, td {
            padding: 15px;
            text-align: left;
            font-size: 1.2em;
            color: #111;
            border: 4px solid #ff3e00; /* Border in theme color */
            transition: background-color 0.3s ease;
        }

        th {
            background-color: rgba(213, 213, 213  , 0.95); /* Light background for header */
            text-transform: uppercase;
            font-weight: 600;
        }

        td {
            background-color: rgba(213, 213, 213  , 0.95);
        }

        tr:hover td {
            background-color: #ff3e00; /* Highlighted on hover */
            color: #fff;

        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .starfield {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .star {
            position: absolute;
            width: 2px;
            height: 2px;
            background: #ff3e00;
            border-radius: 50%;
            animation: twinkling 3s infinite;
        }

        @keyframes twinkling {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        @media only screen and (max-width: 768px) {
            .latest-info {
                flex-direction: column;
                align-items: center;
            }

            .info-box {
                margin: 10px 0;
                width: 90%;
            }
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }
    </style>
</head>
<body>

<div class="starfield" id="starfield"></div>

<h1>🚗 Parking Info 🚗</h1>

<div class="section-header">Latest Parking Info</div>
<div class="latest-info">
    <div class="info-box">
        <h2>Card ID</h2>
        <p><?= htmlspecialchars($result['card_id'] ?? 'No Data') ?></p>
    </div>
    <div class="info-box">
        <h2>License Plate</h2>
        <p><?= htmlspecialchars($result['user_license_plate'] ?? 'No Data') ?></p>
    </div>
    <div class="info-box">
        <h2>Zone</h2>
        <p><?= htmlspecialchars($result['parked_zone'] ?? 'No Data') ?></p>
    </div>
    <div class="info-box">
        <h2>Bay</h2>
        <p><?= htmlspecialchars($result['bay_name'] ?? 'No Data') ?></p>
    </div>
    <div class="info-box">
        <h2>Parking Spot</h2>
        <p><?= htmlspecialchars($result['number'] ?? 'No Data') ?></p>
    </div>
</div>

<div class="section-header">Parking Information</div>
<table>
    <thead>
        <tr>
            <th>NO.</th>
            <th>Card ID</th>
            <th>License Plate</th>
            <th>Zone</th>
            <th>Bay:</th>
            <th>Parking Spot</th>
        </tr>
    </thead>
    <tbody>
        <?php $index = 1; ?>
        <?php foreach ($cards as $card): ?>
        <tr>
            <td><?= $index++ ?></td>
            <td><?= htmlspecialchars($card['card_id']) ?></td>
            <td><?= htmlspecialchars($card['user_license_plate']) ?></td>
            <td><?= htmlspecialchars($card['parked_zone']) ?></td>
            <td><?= htmlspecialchars($card['bay_name']) ?></td>
            <td><?= htmlspecialchars($card['number']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    setTimeout(function() {
        window.location.reload(1);
    }, 10000);

    function createStar() {
        const star = document.createElement("div");
        star.classList.add("star");
        star.style.width = `${Math.random() * 3}px`;
        star.style.height = `${Math.random() * 3}px`;
        star.style.top = `${Math.random() * 100}vh`;
        star.style.left = `${Math.random() * 100}vw`;
        document.getElementById("starfield").appendChild(star);

        setTimeout(() => {
            star.remove();
        }, 3000);
    }

    setInterval(createStar, 100);
</script>
    <!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>