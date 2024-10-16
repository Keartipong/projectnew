<?php
session_start();
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $employee_id = $_POST['employee_id'];

    // Dummy login credentials for demonstration
    if ($username === 'demo' && $password === 'password' && $employee_id === '12345') {
        $_SESSION['loggedin'] = true;
        header("location: dashboard.php");
    } else {
        $error = "Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quantum Car Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.14/lottie.min.js"></script>
    <style>
        body { 
            font-family: 'Orbitron', sans-serif;
            background: radial-gradient(circle at 20% 20%, #000000, #1a1a1a, #000000);
            margin: 0;
            overflow: hidden;
            color: #00f7ff;
        }

        #particles-js, #quantum-particles { 
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(45deg, rgba(0, 0, 0, 0.8), rgba(20, 20, 20, 0.9)), url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            filter: blur(2px);
        }

        /* Enhanced login container */
        .login-container {
            position: relative; 
            z-index: 10; 
            backdrop-filter: blur(12px); 
            background-color: rgba(0, 0, 0, 0.85); 
            border: 2px solid #00f7ff; 
            box-shadow: 0 0 25px #00f7ff, inset 0 0 20px #ff00ff; 
            animation: pulse 2.5s infinite alternate; 
            padding: 40px; 
            border-radius: 20px; 
            transition: 0.5s ease;
        }

        .login-container:hover {
            transform: scale(1.05); 
            box-shadow: 0 0 40px #ff00ff, inset 0 0 30px #00f7ff;
        }

        @keyframes pulse {
            0%, 100% { 
                box-shadow: 0 0 25px #00f7ff, inset 0 0 15px #ff00ff; 
            }
            50% { 
                box-shadow: 0 0 40px #00f7ff, inset 0 0 25px #ff00ff; 
            }
        }

        @keyframes neonPulse {
            0%, 100% {
                text-shadow: 0 0 8px #ff00ff, 0 0 15px #00f7ff;
            }
            50% {
                text-shadow: 0 0 18px #ff00ff, 0 0 35px #00f7ff;
            }
        }
        .login-title {
            text-align: center;
            font-size: 3rem;
            animation: neonPulse 2s ease-in-out infinite;
        }
        .input-group { 
            position: relative; 
            margin-bottom: 30px; 
        }

        .input-group input { 
            width: 100%;
            padding: 12px 45px 12px 15px;
            border: none;
            border-bottom: 3px solid #00f7ff;
            background-color: rgba(0, 247, 255, 0.15);
            color: #fff;
            font-family: 'Orbitron', sans-serif;
            transition: all 0.4s ease;
            border-radius: 5px;
            box-shadow: 0 0 12px #00f7ff, inset 0 0 15px #ff00ff;
        }

        .input-group input:focus { 
            outline: none; 
            border-color: #ff00ff; 
            box-shadow: 0 0 20px #ff00ff, inset 0 0 20px #00f7ff; 
        }

        .input-group label { 
            position: absolute; 
            top: 0; 
            left: 10px; 
            padding: 12px; 
            color: #00f7ff; 
            transition: all 0.4s ease; 
            pointer-events: none; 
        }

        .input-group input:focus + label, 
        .input-group input:not(:placeholder-shown) + label { 
            top: -25px; 
            font-size: 14px; 
            color: #ff00ff; 
        }

        .input-group i { 
            position: absolute; 
            right: 15px; 
            top: 50%; 
            transform: translateY(-50%); 
            color: #00f7ff; 
            transition: all 0.4s; 
        }

        .input-group input:focus + i { 
            color: #ff00ff; 
        }

        /* Enhanced button with 3D effect */
        .cyber-button { 
            position: relative; 
            padding: 16px 40px; 
            border: none; 
            background: linear-gradient(45deg, #00f7ff, #ff00ff); 
            color: #fff; 
            font-family: 'Orbitron', sans-serif; 
            font-weight: bold; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            overflow: hidden; 
            transition: 0.5s; 
            cursor: pointer; 
            border-radius: 50px; 
            box-shadow: 0 0 25px #00f7ff, 0 0 50px #ff00ff, 0 0 75px #ff00ff; 
            transform: translateZ(0);
        }

        .cyber-button:hover { 
            background: linear-gradient(45deg, #ff00ff, #00f7ff); 
            box-shadow: 0 0 30px #ff00ff, 0 0 60px #00f7ff, 0 0 90px #00f7ff; 
            transform: translateZ(10px);
        }

        .cyber-button span { 
            position: absolute; 
            display: block; 
        }

        .cyber-button span:nth-child(1) { 
            top: 0; left: -100%; 
            width: 100%; 
            height: 2px; 
            background: linear-gradient(90deg, transparent, #00f7ff); 
            animation: btn-anim1 1s linear infinite; 
        }

        @keyframes btn-anim1 { 
            0% { left: -100%; } 
            50%, 100% { left: 100%; } 
        }

        .cyber-button span:nth-child(2) { 
            top: -100%; right: 0; 
            width: 2px; 
            height: 100%; 
            background: linear-gradient(180deg, transparent, #00f7ff); 
            animation: btn-anim2 1s linear infinite; 
            animation-delay: .25s; 
        }

        @keyframes btn-anim2 { 
            0% { top: -100%; } 
            50%, 100% { top: 100%; } 
        }

        .cyber-button span:nth-child(3) { 
            bottom: 0; right: -100%; 
            width: 100%; 
            height: 2px; 
            background: linear-gradient(270deg, transparent, #00f7ff); 
            animation: btn-anim3 1s linear infinite; 
            animation-delay: .5s; 
        }

        @keyframes btn-anim3 { 
            0% { right: -100%; } 
            50%, 100% { right: 100%; } 
        }

        .cyber-button span:nth-child(4) { 
            bottom: -100%; left: 0; 
            width: 2px; 
            height: 100%; 
            background: linear-gradient(360deg, transparent, #00f7ff); 
            animation: btn-anim4 1s linear infinite; 
            animation-delay: .75s; 
        }

        /* Error message */
        #error-message { 
            color: #ff0000; 
            text-align: center; 
            margin-top: 10px; 
            animation: shake 0.82s cubic-bezier(.36,.07,.19,.97) both; 
        }

        /* Special animations for icons */
        .flying-car { 
            position: absolute; 
            width: 120px; 
            height: 60px; 
            background: url('car-icon.png') no-repeat center center; 
            background-size: contain; 
            animation: fly 8s linear infinite; 
        }

        @keyframes fly { 
            0% { left: -120px; top: 80%; } 
            50% { left: 50%; top: 20%; } 
            100% { left: 110%; top: 80%; } 
        }

        /* Keyframes for shaking error message */
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }

    </style>
</head>
<body>
    <div id="particles-js"></div>
    <div class="flying-car"></div>
    <div class="min-h-screen flex items-center justify-center">
        <div class="login-container">
            <h1 class="text-3xl text-center mb-8">Quantum Car System Login</h1>
            <form method="POST">
                <div class="input-group">
                    <input type="text" name="username" placeholder=" " required>
                    <label for="username">Username</label>
                    <i class="fa fa-user"></i>
                </div>

                <div class="input-group">
                    <input type="password" name="password" placeholder=" " required>
                    <label for="password">Password</label>
                    <i class="fa fa-lock"></i>
                </div>

                <div class="input-group">
                    <input type="text" name="employee_id" placeholder=" " required>
                    <label for="employee_id">Employee ID</label>
                    <i class="fa fa-id-badge"></i>
                </div>

                <button class="cyber-button" type="submit">
                    <span></span><span></span><span></span><span></span>
                    Login
                </button>

                <?php if($error): ?>
                    <div id="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div id="lottie-container"></div>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
    <script>
        particlesJS('particles-js', {
            particles: { number: { value: 80, density: { enable: true, value_area: 800 } }, color: { value: "#00f7ff" }, shape: { type: "circle" }, opacity: { value: 0.5, random: true },
                size: { value: 3, random: true }, line_linked: { enable: true, distance: 150, color: "#00f7ff", opacity: 0.4, width: 1 },
                move: { enable: true, speed: 6, direction: "none", random: false, straight: false, out_mode: "out", bounce: false }
            },
            interactivity: { detect_on: "canvas", events: { onhover: { enable: true, mode: "repulse" }, onclick: { enable: true, mode: "push" }, resize: true },
                modes: { repulse: { distance: 100, duration: 0.4 }, push: { particles_nb: 4 } }
            },
            retina_detect: true
        });

        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, 1, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ alpha: true });
        renderer.setSize(300, 300);
        document.getElementById('carHologram').appendChild(renderer.domElement);

        const geometry = new THREE.BoxGeometry(1, 0.5, 2);
        const material = new THREE.MeshBasicMaterial({ color: 0x00f7ff, wireframe: true });
        const car = new THREE.Mesh(geometry, material);
        scene.add(car);

        camera.position.z = 3;

        function animate() {
            requestAnimationFrame(animate);
            car.rotation.y += 0.01;
            car.rotation.x += 0.005;
            renderer.render(scene, camera);
        }
        animate();

        gsap.from(".login-container", { duration: 1, y: 50, opacity: 0, ease: "power3.out" });
        gsap.from("input, button", { duration: 0.5, y: 20, opacity: 0, stagger: 0.2, ease: "power2.out", delay: 0.5 });

        const lottieAnimation = lottie.loadAnimation({
            container: document.getElementById('lottie-container'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: 'https://assets2.lottiefiles.com/packages/lf20_yyitq4uc.json'
        });

        const hologram = document.getElementById('carHologram');
        hologram.addEventListener('mousemove', (e) => {
            const rect = hologram.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width * 2 - 1;
            const y = -((e.clientY - rect.top) / rect.height) * 2 + 1;
            gsap.to(car.rotation, { x: y * 0.5, y: x * 0.5, duration: 0.5 });
        });

        hologram.addEventListener('mouseleave', () => {
            gsap.to(car.rotation, { x: 0, y: 0, duration: 0.5 });
        });

        const button = document.querySelector('.cyber-button');
        button.addEventListener('click', function(e) {
            let x = e.clientX - e.target.offsetLeft;
            let y = e.clientY - e.target.offsetTop;
            
            let ripples = document.createElement('span');
            ripples.style.left = x + 'px';
            ripples.style.top = y + 'px';
            this.appendChild(ripples);

            setTimeout(() => {
                ripples.remove()
            },1000);
        });

        gsap.to('.flying-car', {
            y: '+=20',
            duration: 2,
            repeat: -1,
            yoyo: true,
            ease: 'power1.inOut'
        });

        const quantumParticles = document.getElementById('quantum-particles');
        for (let i = 0; i < 50; i++) {
            const particle = document.createElement('div');
            particle.classList.add('quantum-particle');
            particle.style.left = `${Math.random() * 100}%`;
            particle.style.top = `${Math.random() * 100}%`;
            particle.style.animationDelay = `${Math.random() * 5}s`;
            quantumParticles.appendChild(particle);
        }

        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                gsap.to(input, { boxShadow: '0 0 20px #ff00ff', duration: 0.3 });
            });
            input.addEventListener('blur', () => {
                gsap.to(input, { boxShadow: 'none', duration: 0.3 });
            });
        });

        document.addEventListener('mousemove', (e) => {
            const loginContainer = document.querySelector('.login-container');
            const xAxis = (window.innerWidth / 2 - e.pageX) / 25;
            const yAxis = (window.innerHeight / 2 - e.pageY) / 25;
            gsap.to(loginContainer, { rotationY: xAxis, rotationX: yAxis, duration: 0.5 });
        });
    </script>
</body>
</html>