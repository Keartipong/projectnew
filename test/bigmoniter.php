<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลป้ายทะเบียนและที่จอดรถ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: rgb(0, 0, 0);
            color: rgb(255, 255, 255);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 80%;
            max-width: 800px;
            margin-top: 20px;
        }
        .header {
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
            
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table th, .info-table td {
            border: 1px solid white;
            padding: 10px;
            text-align: center;
        }
        .info-table th {
            background-color: green;
            color: rgb(255, 255, 255);
        }
    </style>
</head>
<body>
    <div class="header">ข้อมูลป้ายทะเบียนและที่จอดรถ</div>
    <div class="container">
        <table class="info-table">
            <thead>
                <tr>
                    <th>ป้ายทะเบียน</th>
                    <th>โซน</th>
                    <th>ช่องจอด</th>
                </tr>
            </thead>
            <tbody id="info-table-body">
                <!-- ข้อมูลจะถูกเพิ่มที่นี่โดย JavaScript -->
            </tbody>
        </table>
    </div>

    <script class="boxxer">
        
        // ข้อมูลตัวอย่าง สามารถดึงข้อมูลนี้จากฐานข้อมูลหรือ API
        const parkingData = [
            { licensePlate: 'กข 1234', zone: 'A', lot: 'A101' },
            { licensePlate: 'ขค 5678', zone: 'B', lot: 'B203' },
            { licensePlate: 'คน 9101', zone: 'C', lot: 'C302' },
            { licensePlate: 'นม 1121', zone: 'D', lot: 'D404' },
            { licensePlate: 'พย 3141', zone: 'E', lot: 'E205' }
        ];

        // ฟังก์ชันสำหรับการแสดงข้อมูล
        function displayParkingInfo() {
            const tableBody = document.getElementById('info-table-body');
            tableBody.innerHTML = '';

            parkingData.forEach(data => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${data.licensePlate}</td>
                    <td>${data.zone}</td>
                    <td>${data.lot}</td>
                `;
                tableBody.appendChild(row);
            });
        }

        // เรียกฟังก์ชันเมื่อโหลดหน้าเว็บเสร็จสิ้น
        document.addEventListener('DOMContentLoaded', displayParkingInfo);
    </script>
</body>
</html>
