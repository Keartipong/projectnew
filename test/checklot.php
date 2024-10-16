<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Lot</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;700&display=swap');

        body {
            font-family: 'Prompt', sans-serif;
            background: linear-gradient(to right, #ece9e6, #ffffff);
            color: #37474f;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .container {
            display: flex;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1200px;
            margin-top: 20px;
            overflow: hidden;
        }

        .column-labels {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-right: 40px;
            align-items: center;
        }

        .column-label {
            background-color: #455a64;
            color: white;
            text-align: center;
            padding: 15px 25px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .column-label:hover {
            background-color: #607d8b;
            transform: scale(1.05);
        }

        .column-label.active {
            background-color: #03a9f4;
        }

        .lot-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
        }

        .lot-row {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .lot {
            color: white;
            text-align: center;
            padding: 25px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background-color 0.3s ease, transform 0.2s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            flex: 1 0 100px; /* ขนาดสำหรับ 4 ช่องจอดในแถว */
        }

        .lot-5th {
            flex: 1 0 80px; /* ขนาดสำหรับ 5 ช่องจอดในแถวที่ 7 */
        }

        .lot:hover {
            background-color: #66bb6a;
            transform: translateY(-5px);
        }

        .legend {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 30px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #37474f;
            font-weight: 500;
        }

        .legend-color {
            width: 25px;
            height: 25px;
            border-radius: 5px;
        }

        .vacant {
            background-color: #81c784;
        }

        .reserved {
            background-color: #ffb74d;
        }

        .occupied {
            background-color: #64b5f6;
        }

        .zone-name {
            font-size: 28px;
            margin-bottom: 25px;
            color: white;
            background-color: #607d8b;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            width: 100%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .zone-name:hover {
            transform: scale(1.02);
        }
    </style>
</head>
<body>
    <div class="zone-name">โซน: A</div>
    <div class="container">
        <div class="column-labels">
            <div class="column-label active" data-zone="A">A</div>
            <div class="column-label" data-zone="B">B</div>
            <div class="column-label" data-zone="C">C</div>
            <div class="column-label" data-zone="D">D</div>
            <div class="column-label" data-zone="E">E</div>
            <div class="column-label" data-zone="F">F</div>
            <div class="column-label" data-zone="G">G</div>
            <div class="column-label" data-zone="H">H</div>
        </div>
        <div class="lot-container">
            <!-- ลานจอดรถจะแสดงที่นี่ -->
        </div>
    </div>
    <div class="legend">
        <div class="legend-item">
            <div class="legend-color vacant"></div> : ว่าง
        </div>
        <div class="legend-item">
            <div class="legend-color reserved"></div> : จอง
        </div>
        <div class="legend-item">
            <div class="legend-color occupied"></div> : จอด
        </div>
    </div>

    <script>
        const lotData = {
            A: [
                { number: "A101", status: 1 }, { number: "A102", status: 1 }, { number: "A103", status: 1 }, { number: "A104", status: 1 },
                { number: "A201", status: 1 }, { number: "A202", status: 1 }, { number: "A203", status: 1 }, { number: "A204", status: 1 },
                { number: "A301", status: 1 }, { number: "A302", status: 1 }, { number: "A303", status: 1 }, { number: "A304", status: 1 },
                { number: "A401", status: 1 }, { number: "A402", status: 1 }, { number: "A403", status: 1 }, { number: "A404", status: 1 },
                { number: "A501", status: 1 }, { number: "A502", status: 1 }, { number: "A503", status: 1 }, { number: "A504", status: 1 },
                { number: "A601", status: 1 }, { number: "A602", status: 1 }, { number: "A603", status: 1 }, { number: "A604", status: 1 },
                { number: "A701", status: 1 }, { number: "A702", status: 1 }, { number: "A703", status: 1 }, { number: "A704", status: 1 }, { number: "A705", status: 1 }
            ],
            B: [
                { number: "B101", status: 2 }, { number: "B102", status: 2 }, { number: "B103", status: 2 }, { number: "B104", status: 2 },
                { number: "B201", status: 2 }, { number: "B202", status: 2 }, { number: "B203", status: 2 }, { number: "B204", status: 2 },
                { number: "B301", status: 2 }, { number: "B302", status: 2 }, { number: "B303", status: 2 }, { number: "B304", status: 2 },
                { number: "B401", status: 2 }, { number: "B402", status: 2 }, { number: "B403", status: 2 }, { number: "B404", status: 2 },
                { number: "B501", status: 2 }, { number: "B502", status: 2 }, { number: "B503", status: 2 }, { number: "B504", status: 2 },
                { number: "B601", status: 2 }, { number: "B602", status: 2 }, { number: "B603", status: 2 }, { number: "B604", status: 2 },
                { number: "B701", status: 2 }, { number: "B702", status: 2 }, { number: "B703", status: 2 }, { number: "B704", status: 2 }, { number: "B705", status: 2 }
            ],
            C: [
                { number: "C101", status: 3 }, { number: "C102", status: 3 }, { number: "C103", status: 3 }, { number: "C104", status: 3 },
                { number: "C201", status: 3 }, { number: "C202", status: 3 }, { number: "C203", status: 3 }, { number: "C204", status: 3 },
                { number: "C301", status: 3 }, { number: "C302", status: 3 }, { number: "C303", status: 3 }, { number: "C304", status: 3 },
                { number: "C401", status: 3 }, { number: "C402", status: 3 }, { number: "C403", status: 3 }, { number: "C404", status: 3 },
                { number: "C501", status: 3 }, { number: "C502", status: 3 }, { number: "C503", status: 3 }, { number: "C504", status: 3 },
                { number: "C601", status: 3 }, { number: "C602", status: 3 }, { number: "C603", status: 3 }, { number: "C604", status: 3 },
                { number: "C701", status: 3 }, { number: "C702", status: 3 }, { number: "C703", status: 3 }, { number: "C704", status: 3 }, { number: "C705", status: 3 }
            ],
            D: [
                { number: "D101", status: 1 }, { number: "D102", status: 1 }, { number: "D103", status: 1 }, { number: "D104", status: 1 },
                { number: "D201", status: 1 }, { number: "D202", status: 1 }, { number: "D203", status: 1 }, { number: "D204", status: 1 },
                { number: "D301", status: 1 }, { number: "D302", status: 1 }, { number: "D303", status: 1 }, { number: "D304", status: 1 },
                { number: "D401", status: 1 }, { number: "D402", status: 1 }, { number: "D403", status: 1 }, { number: "D404", status: 1 },
                { number: "D501", status: 1 }, { number: "D502", status: 1 }, { number: "D503", status: 1 }, { number: "D504", status: 1 },
                { number: "D601", status: 1 }, { number: "D602", status: 1 }, { number: "D603", status: 1 }, { number: "D604", status: 1 },
                { number: "D701", status: 1 }, { number: "D702", status: 1 }, { number: "D703", status: 1 }, { number: "D704", status: 1 }, { number: "D705", status: 1 }
            ],
            E: [
                { number: "E101", status: 2 }, { number: "E102", status: 2 }, { number: "E103", status: 2 }, { number: "E104", status: 2 },
                { number: "E201", status: 2 }, { number: "E202", status: 2 }, { number: "E203", status: 2 }, { number: "E204", status: 2 },
                { number: "E301", status: 2 }, { number: "E302", status: 2 }, { number: "E303", status: 2 }, { number: "E304", status: 2 },
                { number: "E401", status: 2 }, { number: "E402", status: 2 }, { number: "E403", status: 2 }, { number: "E404", status: 2 },
                { number: "E501", status: 2 }, { number: "E502", status: 2 }, { number: "E503", status: 2 }, { number: "E504", status: 2 },
                { number: "E601", status: 2 }, { number: "E602", status: 2 }, { number: "E603", status: 2 }, { number: "E604", status: 2 },
                { number: "E701", status: 2 }, { number: "E702", status: 2 }, { number: "E703", status: 2 }, { number: "E704", status: 2 }, { number: "E705", status: 2 }
            ],
            F: [
                { number: "F101", status: 3 }, { number: "F102", status: 3 }, { number: "F103", status: 3 }, { number: "F104", status: 3 },
                { number: "F201", status: 3 }, { number: "F202", status: 3 }, { number: "F203", status: 3 }, { number: "F204", status: 3 },
                { number: "F301", status: 3 }, { number: "F302", status: 3 }, { number: "F303", status: 3 }, { number: "F304", status: 3 },
                { number: "F401", status: 3 }, { number: "F402", status: 3 }, { number: "F403", status: 3 }, { number: "F404", status: 3 },
                { number: "F501", status: 3 }, { number: "F502", status: 3 }, { number: "F503", status: 3 }, { number: "F504", status: 3 },
                { number: "F601", status: 3 }, { number: "F602", status: 3 }, { number: "F603", status: 3 }, { number: "F604", status: 3 },
                { number: "F701", status: 3 }, { number: "F702", status: 3 }, { number: "F703", status: 3 }, { number: "F704", status: 3 }, { number: "F705", status: 3 }
            ],
            G: [
                { number: "G101", status: 1 }, { number: "G102", status: 1 }, { number: "G103", status: 1 }, { number: "G104", status: 1 },
                { number: "G201", status: 1 }, { number: "G202", status: 1 }, { number: "G203", status: 1 }, { number: "G204", status: 1 },
                { number: "G301", status: 1 }, { number: "G302", status: 1 }, { number: "G303", status: 1 }, { number: "G304", status: 1 },
                { number: "G401", status: 1 }, { number: "G402", status: 1 }, { number: "G403", status: 1 }, { number: "G404", status: 1 },
                { number: "G501", status: 1 }, { number: "G502", status: 1 }, { number: "G503", status: 1 }, { number: "G504", status: 1 },
                { number: "G601", status: 1 }, { number: "G602", status: 1 }, { number: "G603", status: 1 }, { number: "G604", status: 1 },
                { number: "G701", status: 1 }, { number: "G702", status: 1 }, { number: "G703", status: 1 }, { number: "G704", status: 1 }, { number: "G705", status: 1 }
            ],
            H: [
                { number: "H101", status: 2 }, { number: "H102", status: 2 }, { number: "H103", status: 2 }, { number: "H104", status: 2 },
                { number: "H201", status: 2 }, { number: "H202", status: 2 }, { number: "H203", status: 2 }, { number: "H204", status: 2 },
                { number: "H301", status: 2 }, { number: "H302", status: 2 }, { number: "H303", status: 2 }, { number: "H304", status: 2 },
                { number: "H401", status: 2 }, { number: "H402", status: 2 }, { number: "H403", status: 2 }, { number: "H404", status: 2 },
                { number: "H501", status: 2 }, { number: "H502", status: 2 }, { number: "H503", status: 2 }, { number: "H504", status: 2 },
                { number: "H601", status: 2 }, { number: "H602", status: 2 }, { number: "H603", status: 2 }, { number: "H604", status: 2 },
                { number: "H701", status: 2 }, { number: "H702", status: 2 }, { number: "H703", status: 2 }, { number: "H704", status: 2 }, { number: "H705", status: 2 }
            ]
        };
        

        document.addEventListener("DOMContentLoaded", function() {
            const labels = document.querySelectorAll(".column-label");
            const lotContainer = document.querySelector(".lot-container");
            const zoneName = document.querySelector(".zone-name");

            labels.forEach(label => {
                label.addEventListener("click", function() {
                    const zone = label.dataset.zone;

                    // เปลี่ยนลานจอดรถตามโซน
                    updateLots(zone);

                    // เปลี่ยนสถานะ active ของปุ่ม
                    labels.forEach(l => l.classList.remove("active"));
                    label.classList.add("active");

                    // เปลี่ยนชื่อโซน
                    zoneName.textContent = `โซน: ${zone}`;
                });
            });

            // แสดงข้อมูลของโซนเริ่มต้น (โซน A)
            updateLots("A");
        });

        function updateLots(zone) {
            const lots = lotData[zone];
            let lotsHtml = "";

            // แสดงแถวที่ 1-6 (มี 4 ช่องจอด)
            for (let i = 0; i < 6; i++) {
                lotsHtml += `<div class="lot-row">`;
                for (let j = 0; j < 4; j++) {
                    lotsHtml += `<div class="lot" style="background-color: ${getColor(lots[i * 4 + j].status)}">${lots[i * 4 + j].number}</div>`;
                }
                lotsHtml += `</div>`;
            }

            // แสดงแถวที่ 7 (มี 5 ช่องจอด)
            lotsHtml += `<div class="lot-row">`;
            for (let i = 24; i < 29; i++) {
                lotsHtml += `<div class="lot lot-5th" style="background-color: ${getColor(lots[i].status)}">${lots[i].number}</div>`;
            }
            lotsHtml += `</div>`;

            document.querySelector(".lot-container").innerHTML = lotsHtml;
        }

        function getColor(status) {
            switch (status) {
                case 1: return "#81c784"; // ว่าง
                case 2: return "#ffb74d"; // จอง
                case 3: return "#64b5f6"; // จอด
                default: return "#e0e0e0"; // สีเริ่มต้นสำหรับสถานะไม่ระบุ
            }
        }
    </script>
</body>
</html>
