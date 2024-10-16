const WebSocket = require('ws');
const mysql = require('mysql');

// สร้างการเชื่อมต่อฐานข้อมูล
const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'carlot'
});

connection.connect();

// สร้าง WebSocket Server
const wss = new WebSocket.Server({ port: 8080 });

let lastCardId = null;

wss.on('connection', ws => {
  console.log('Client connected');

  const checkForUpdates = () => {
    const sql = "SELECT card_id FROM distance_data ORDER BY distance_id DESC LIMIT 1";
    
    connection.query(sql, (err, results) => {
      if (err) throw err;

      if (results.length > 0) {
        const newCardId = results[0].card_id;

        if (newCardId !== lastCardId) {
          lastCardId = newCardId;
          const query = `
            SELECT d.distance, c.user_license_plate 
            FROM distance_data d
            JOIN card c ON d.card_id = c.card_id
            WHERE d.card_id = ?
            ORDER BY d.distance_id DESC
            LIMIT 1
          `;
          connection.query(query, [newCardId], (err, results) => {
            if (err) throw err;

            if (results.length > 0) {
              const data = results[0];
              const statusCheckQuery = `
                SELECT COUNT(*) as total_lots, 
                       SUM(CASE WHEN status_id IN (6, 7, 3) THEN 1 ELSE 0 END) as full_lots 
                FROM lot
              `;
              connection.query(statusCheckQuery, (err, statusResults) => {
                if (err) throw err;

                const statusRow = statusResults[0];
                let parking_slot = '';
                let bay_name = 'N/A'; // Default value
                let parked_zone = 'Unknown'; // Default zone

                if (statusRow.total_lots === statusRow.full_lots) {
                  parking_slot = "All parking slots are full.";
                } else {
                  let lotSql;
                  if (data.distance > 190) {
                    lotSql = "SELECT number, bay_id FROM lot WHERE parking_type_id = 1 AND status_id = 1";
                  } else {
                    // ใช้ SQL ที่คุณให้มาในที่นี้สำหรับการจัดการที่จอดเมื่อระยะห่างน้อยกว่า 190 เมตร
                    lotSql = `
                      SELECT number, bay_id 
                      FROM lot 
                      WHERE status_id = 1 
                        AND bay_id IN (1, 2, 3, 4, 5, 6, 7, 8)
                      ORDER BY 
                        CASE 
                          WHEN number LIKE 'A1%' THEN 1
                          WHEN number LIKE 'B1%' THEN 2
                          WHEN number LIKE 'C1%' THEN 3
                          WHEN number LIKE 'D1%' THEN 4
                          WHEN number LIKE 'E1%' THEN 5
                          WHEN number LIKE 'F1%' THEN 6
                          WHEN number LIKE 'G1%' THEN 7
                          WHEN number LIKE 'H1%' THEN 8
                        END,
                        CAST(SUBSTRING(number, 2) AS UNSIGNED)
                    `;
                  }

                  connection.query(lotSql, (err, lotResults) => {
                    if (err) throw err;

                    if (lotResults.length > 0) {
                      const selectedLot = lotResults[0];
                      const lotNumber = selectedLot.number;
                      const bayId = selectedLot.bay_id;

                      console.log(`Selected lot number: ${lotNumber}, bay_id: ${bayId}`);

                      const baySql = "SELECT bay_name FROM bay WHERE bay_id = ?";
                      connection.query(baySql, [bayId], (err, bayResults) => {
                        if (err) throw err;

                        if (bayResults.length > 0) {
                          bay_name = bayResults[0].bay_name;
                          console.log(`Bay name: ${bay_name}`);

                          // ตรวจสอบโซนที่จอดตามชื่อโซน
                          if (['A', 'B'].includes(bay_name)) {
                            parked_zone = 1;
                          } else if (['C', 'D', 'E', 'F', 'G', 'H'].includes(bay_name)) {
                            parked_zone = 2;
                          }
                        } else {
                          console.log(`No bay found for bay_id: ${bayId}`);
                        }

                        parking_slot = `Parking slot: ${lotNumber}`;
                        ws.send(JSON.stringify({
                          card_id: newCardId,
                          distance: data.distance,
                          user_license_plate: data.user_license_plate,
                          parking_slot,
                          bay_name,
                          parked_zone,  // ส่งข้อมูลโซนที่จอดไปด้วย
                          last_update: Date.now()
                        }));
                      });
                    } else {
                      parking_slot = "No available parking slots.";
                      ws.send(JSON.stringify({
                        card_id: newCardId,
                        distance: data.distance,
                        user_license_plate: data.user_license_plate,
                        parking_slot,
                        bay_name: 'N/A',
                        parked_zone: 'Unknown',
                        last_update: Date.now()
                      }));
                    }
                  });
                }
              });
            }
          });
        }
      }
    });
  };

  checkForUpdates();

  const interval = setInterval(checkForUpdates, 1000);

  ws.on('close', () => {
    console.log('Client disconnected');
    clearInterval(interval);
  });
});

console.log('WebSocket server is running on ws://localhost:8080');
