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
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ที่จอดรถ</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #0d0d0d;
            background-image: url('img/1.jpg'); /* Racing car background */
            color: #ffffff;
            text-align: center;
            margin: 0;
            padding: 0;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        h1 {
            margin-top: 40px;
            color: #ffcc00;
            font-size: 4em;
            letter-spacing: 4px;
            text-shadow: 2px 2px 10px #000;
            font-family: 'Roboto', sans-serif;
        }

        /* Row Grid Styles */
        .row-grid {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 20px;
            margin: 50px auto;
            max-width: 1000px;
            padding: 20px;
        }

        .row-label {
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            background-color: rgba(255, 136, 0, 0.9);
            border-radius: 15px;
            font-size: 1.8em;
            padding: 20px;
            cursor: pointer;
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.8);
            transition: background-color 0.4s ease, transform 0.3s ease, box-shadow 0.3s ease;
        }

        .row-label:hover {
            background-color: rgba(255, 211, 0, 0.9);
            transform: translateY(-8px);
            box-shadow: 0 0 30px rgba(255, 255, 0, 0.7);
        }

        /* Parking Grid Table Styles */
        .parking-grid-wrapper {
            margin: 50px auto;
            max-width: 1100px;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0px 20px 40px rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(8px);
        }

        .parking-grid {
            display: none;
            margin: 20px auto;
            border-collapse: collapse;
            width: 100%;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        table {
            width: 100%;
            border: 2px solid #444;
            margin: 20px 0;
            border-collapse: collapse;
            font-size: 1.4em;
            color: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
        }

        th, td {
            padding: 15px;
            text-align: center;
            border: 2px solid #333;
            position: relative;
        }

        th {
            background-color: rgba(255, 204, 0, 0.9);
            color: #000;
            font-size: 1.5em;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        td {
            transition: background-color 0.3s ease;
            color: #ffffff;
        }

        /* Parking Spot Status Colors */
        .available {
            background-color: rgba(41, 182, 246, 0.9);
            color: #000;
            box-shadow: 0 0 10px rgba(41, 182, 246, 0.7);
        }

        .available:hover {
            background-color: rgba(33, 150, 243, 1);
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(41, 182, 246, 1);
        }

        .occupied {
            background-color: rgba(0, 255, 0, 0.9);
            color: #000;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.7);
        }

        .occupied:hover {
            background-color: rgba(50, 205, 50, 1);
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(0, 255, 0, 1);
        }

        .reserved {
            background-color: rgba(255, 255, 0, 0.9);
            color: #000;
            box-shadow: 0 0 10px rgba(255, 255, 0, 0.7);
        }

        .reserved:hover {
            background-color: rgba(255, 215, 0, 1);
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(255, 255, 0, 1);
        }

        .broken {
            background-color: rgba(255, 0, 0, 0.9);
            color: #fff;
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.7);
        }

        .broken:hover {
            background-color: rgba(255, 69, 0, 1);
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(255, 0, 0, 1);
        }

        /* Legend Section */
        .legend {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            gap: 30px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            font-size: 1.3em;
            color: #ffcc00;
            font-weight: bold;
        }

        .legend-color {
            width: 35px;
            height: 35px;
            margin-right: 10px;
            border-radius: 5px;
        }

        .legend-item span {
            color: #ffffff;
            text-shadow: 1px 1px 5px #000;
        }

        /* Additional Car-Themed Styling */
        .row-label {
            background-image: linear-gradient(45deg, rgba(255, 136, 0, 0.9), rgba(255, 211, 0, 0.9));
        }

        .row-label:hover {
            background-image: linear-gradient(45deg, rgba(255, 211, 0, 1), rgba(255, 136, 0, 1));
        }
    </style>
</head>
<body>
    <h1>ที่จอดรถ</h1>

    <!-- Row Selection Grid -->
    <div class="row-grid">
        <div class="row-label" onclick="showParking('A')">A</div>
        <div class="row-label" onclick="showParking('B')">B</div>
        <div class="row-label" onclick="showParking('C')">C</div>
        <div class="row-label" onclick="showParking('D')">D</div>
        <div class="row-label" onclick="showParking('E')">E</div>
        <div class="row-label" onclick="showParking('F')">F</div>
        <div class="row-label" onclick="showParking('G')">G</div>
        <div class="row-label" onclick="showParking('H')">H</div>
    </div>

    <!-- Parking Table for Each Row -->
    <div class="parking-grid-wrapper">
        <table id="parking-grid" class="parking-grid"></table>
    </div>

    <div class="legend">
        <div class="legend-item">
            <div class="legend-color" style="background-color: #29b6f6;"></div>
            <span>ว่าง</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #ffff00;"></div>
            <span>จอง</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #00ff00;"></div>
            <span>จอด</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #ff0000;"></div>
            <span>เสีย</span>
        </div>
    </div>

    <script>
        async function fetchParkingData() {
            const response = await fetch('fetch_parking_data.php');
            return await response.json();
        }

        function showParking(row) {
            fetchParkingData().then(data => {
                const parkingGrid = document.getElementById('parking-grid');
                parkingGrid.innerHTML = ''; // Clear previous data

                // Create table headers
                let tableHeader = '<tr><th>จุดจอด</th><th>สถานะ</th></tr>';
                parkingGrid.innerHTML += tableHeader;

                // Generate parking spots for the clicked row
                const bayId = getBayId(row); // Get bay_id based on row
                const spots = data.filter(spot => spot.bay_id === bayId); // Filter by bay_id
                for (let i = 1; i <= 7; i++) {
                    for (let j = 1; j <= (i === 7 ? 5 : 4); j++) {
                        const spotNumber = row + i + String(j).padStart(2, '0');
                        const spot = spots.find(s => s.number === spotNumber);
                        let className = 'available';
                        let status = 'ว่าง';
                        let icon = '🚗';

                        if (spot) {
                            if (spot.status_id === 1) {
                                className = 'available';
                                status = 'ว่าง';
                                icon = '🚗';
                            } else if (spot.status_id === 6) {
                                className = 'reserved';
                                status = 'จอง';
                                icon = '🛑';
                            } else if (spot.status_id === 7) {
                                className = 'occupied';
                                status = 'จอด';
                                icon = '🅿️';
                            } else if (spot.status_id === 3) {
                                className = 'broken';
                                status = 'เสีย';
                                icon = '⚠️';
                            }
                        }

                        const rowHTML = `<tr><td>${spotNumber}</td><td class="${className}">${icon} ${status}</td></tr>`;
                        parkingGrid.innerHTML += rowHTML;
                    }
                }

                parkingGrid.style.display = 'table'; // Show the table
            });
        }

        function getBayId(row) {
            // Map row labels to bay_id values
            const mapping = {
                'A': 1,
                'B': 2,
                'C': 3,
                'D': 4,
                'E': 5,
                'F': 6,
                'G': 7,
                'H': 8
            };
            return mapping[row] || 1; 
        }
    </script>
</body>
</html>


