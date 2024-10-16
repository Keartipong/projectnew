<?php
// Database configuration
$host = 'localhost'; // Change this to your database host
$dbname = 'carlot'; // Change this to your database name
$username = 'root'; // Change this to your database username
$password = ''; // Change this to your database password

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Retrieve the latest card ID where status_id is 6
$sql = 'SELECT c.card_id, c.user_license_plate, l.number AS parking_slot, b.bay_name AS zone 
        FROM card c
        JOIN lot l ON c.lot_id = l.lot_id
        JOIN bay b ON l.bay_id = b.bay_id
        WHERE c.status_id = 6
        ORDER BY c.card_id DESC
        LIMIT 1';
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute();
    $carData = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}

// If no data is found, set default values
if (!$carData) {
    $carData = [
        'card_id' => 'Not Found',
        'user_license_plate' => 'Not Found',
        'parking_slot' => 'Not Found',
        'zone' => 'Not Found'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Slot Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
    <style>
        :root {
            --neon-red: #ff0000;
            --dark-bg: #0a0a0a;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Rajdhani', sans-serif;
            background-color: var(--dark-bg);
            background-size: cover;
            background-position: center;
            color: #ffffff;
            overflow: hidden;
            height: 100vh;
            background-repeat: no-repeat;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 10px solid rgba(255, 0, 0, 0.8);
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.8);
            animation: ledBlink 1.5s infinite alternate;
            z-index: -2;
        }
        @keyframes ledBlink {
            0% {
                box-shadow: 0 0 15px rgba(255, 0, 0, 0.5);
            }
            100% {
                box-shadow: 0 0 25px rgba(255, 0, 0, 0.8);
            }
        }
        #threeJsCanvas {
            position: fixed;
            top: 0;
            left: 0;
            z-index: -1;
        }
        .dashboard {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 20px;
            box-sizing: border-box;
        }
        h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 3.5rem;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: var(--neon-red);
            text-shadow: 0 0 10px var(--neon-red), 0 0 20px var(--neon-red), 0 0 30px var(--neon-red);
            animation: pulsate 1.5s infinite alternate;
        }
        .car-data {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            width: 100%;
            max-width: 800px;
            background: rgba(0, 0, 0, 0.7);
            border: 2px solid var(--neon-red);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 0 20px var(--neon-red);
        }
        .data-card {
            text-align: center;
            transition: all 0.3s ease;
        }
        .data-card:hover {
            transform: scale(1.05);
        }
        .data-label {
            font-size: 1.2rem;
            color: var(--neon-red);
            margin-bottom: 10px;
        }
        .data-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #ffffff;
        }
        @keyframes pulsate {
            0% {
                text-shadow: 0 0 10px var(--neon-red), 0 0 20px var(--neon-red);
            }
            100% {
                text-shadow: 0 0 20px var(--neon-red), 0 0 30px var(--neon-red), 0 0 40px var(--neon-red);
            }
        }
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            transition: opacity 0.5s ease-out;
        }
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid var(--neon-red);
            border-top: 5px solid #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
<canvas id="threeJsCanvas"></canvas>
    <div class="dashboard">
        <h1>Parking Slot Details</h1>
        <div class="car-data">
            <div class="data-card">
                <div class="data-label">Card ID</div>
                <div class="data-value"><?= htmlspecialchars($carData['card_id']) ?></div>
            </div>
            <div class="data-card">
                <div class="data-label">ป้ายทะเบียน</div>
                <div class="data-value"><?= htmlspecialchars($carData['user_license_plate']) ?></div>
            </div>
            <div class="data-card">
                <div class="data-label">ช่องจอด</div>
                <div class="data-value"><?= htmlspecialchars($carData['parking_slot']) ?></div>
            </div>
            <div class="data-card">
                <div class="data-label">โซน</div>
                <div class="data-value"><?= htmlspecialchars($carData['zone']) ?></div>
            </div>
        </div>
    </div>


    <script>
        // Three.js setup
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ canvas: document.getElementById('threeJsCanvas'), alpha: true });
        renderer.setSize(window.innerWidth, window.innerHeight);

        // Lighting
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
        scene.add(ambientLight);
        const directionalLight = new THREE.DirectionalLight(0xff0000, 1);
        directionalLight.position.set(5, 5, 5);
        scene.add(directionalLight);

        // Controls
        const controls = new THREE.OrbitControls(camera, renderer.domElement);
        controls.enableDamping = true;
        controls.dampingFactor = 0.25;
        controls.enableZoom = false;

        // Particle system
        const particlesGeometry = new THREE.BufferGeometry();
        const particlesCount = 5000;
        const posArray = new Float32Array(particlesCount * 3);

        for (let i = 0; i < particlesCount * 3; i++) {
            posArray[i] = (Math.random() - 0.5) * 10;
        }

        particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
        const particlesMaterial = new THREE.PointsMaterial({
            size: 0.005,
            color: 0xff0000,
        });

        const particlesMesh = new THREE.Points(particlesGeometry, particlesMaterial);
        scene.add(particlesMesh);

        camera.position.z = 5;

        // Animation function
        function animate() {
            requestAnimationFrame(animate);
            particlesMesh.rotation.y += 0.001;
            controls.update();
            renderer.render(scene, camera);
        }

        // Start animation
        animate();

        // Hide loading overlay
        document.getElementById('loadingOverlay').style.opacity = 0;
        setTimeout(() => {
            document.getElementById('loadingOverlay').style.display = 'none';
        }, 500);

        // Handle window resize
        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });

        // Interactive elements
        document.querySelectorAll('.data-card').forEach(card => {
            card.addEventListener('mouseover', () => {
                card.style.transform = 'scale(1.1)';
            });
            card.addEventListener('mouseout', () => {
                card.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>
