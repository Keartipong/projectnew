<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลรถจอดรถ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            padding: 20px;
        }
        .container {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        h1 {
            color: #333;
        }
        .info {
            margin: 20px 0;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>ข้อมูลรถล่าสุด</h1>
        <div id="info" class="info">
            <p><strong>ป้ายทะเบียน:</strong> <span id="license-plate">ยังไม่มีข้อมูล</span></p>
            <p><strong>โซน:</strong> <span id="zone">ยังไม่มีข้อมูล</span></p>
            <p><strong>ช่องจอด:</strong> <span id="slot">ยังไม่มีข้อมูล</span></p>
        </div>
    </div>
    <script>
        // ฟังก์ชันสำหรับการอัปเดตข้อมูล
        function updateInfo(data) {
            document.getElementById('license-plate').textContent = data.licensePlate || 'ยังไม่มีข้อมูล';
            document.getElementById('zone').textContent = data.zone || 'ยังไม่มีข้อมูล';
            document.getElementById('slot').textContent = data.slot || 'ยังไม่มีข้อมูล';
        }

        // จำลองการดึงข้อมูลใหม่ (ในกรณีจริงคุณจะดึงข้อมูลจาก API หรือฐานข้อมูล)
        function fetchData() {
            // ตัวอย่างข้อมูลใหม่
            const newData = {
                licensePlate: '1234ABCD',
                zone: 'โซน A',
                slot: 'ช่อง A103'
            };
            // อัปเดตข้อมูลบนหน้าเว็บ
            updateInfo(newData);
        }

        // ตั้งค่าให้ฟังก์ชัน fetchData ถูกเรียกทุก 5 วินาที
        setInterval(fetchData, 5000);
    </script>
</body>
</html>
