<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDD - Login</title>
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../dist/sweetalert2/sweetalert2.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            height: 100vh;
            overflow: hidden;
            background: linear-gradient(to bottom right, #003366, #0055a5);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
            /* Tambahkan ini */
        }

        .container {
            position: relative;
            z-index: 10;
            /* Tingkatkan z-index */
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 2.5rem;
            border-radius: 20px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border: 1px solid rgba(255, 255, 255, 0.18);
            animation: fadeIn 0.5s ease-out;
        }

        .icon-container {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .icon-container img {
            max-width: 120px;
            animation: pulse 2s infinite;
        }

        h3 {
            color: white;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.8rem;
            font-weight: 600;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #003366;
            font-size: 1.2rem;
            pointer-events: none;
            /* Tambahkan ini */
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: none;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.9);
            color: #003366;
            font-size: 1rem;
            transition: all 0.3s ease;
            cursor: text;
            /* Tambahkan ini */
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            box-shadow: 0 0 0 2px #ff5722;
            background: white;
        }

        .toggle-password {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            color: white;
            font-size: 0.9rem;
            cursor: pointer;
            /* Tambahkan ini */
        }

        .toggle-password input[type="checkbox"] {
            margin-right: 8px;
            cursor: pointer;
            /* Tambahkan ini */
        }

        input[type="submit"] {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 30px;
            background: #ff5722;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            /* Tambahkan ini */
            z-index: 15;
            /* Tambahkan ini */
        }

        input[type="submit"]:hover {
            background: #e64a19;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(230, 74, 25, 0.4);
        }

        /* Tambahkan style untuk memastikan form bisa diinteraksi */
        form {
            position: relative;
            z-index: 15;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 2rem;
            }

            h3 {
                font-size: 1.5rem;
            }
        }

        /* Tambahkan style untuk membuat tombol lebih responsif */
        input[type="submit"]:active {
            transform: translateY(1px);
        }
    </style>
</head>

<body>
    <div id="particles-js"></div>
    <div class="container">
        <div class="icon-container">
            <img src="../dist/img/sanwa.png" alt="Company Logo">
        </div>
        <h3>Production Display</h3>

        <form id="loginForm" method="post" action="proses_login.php">
            <div class="input-wrapper">
                <i class="fas fa-user-alt"></i>
                <input type="text" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="toggle-password">
                <input type="checkbox" id="toggleCheckbox" onclick="togglePassword()">
                <label for="toggleCheckbox">Show Password</label>
            </div>
            <input type="submit" value="Login">
        </form>
    </div>

    <script src="../particles.min.js"></script>
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../dist/sweetalert2/sweetalert2.min.js"></script>
    <script src="../dist/sweetalert2/sweetalert2.js"></script>

    <script>
        // Inisialisasi particles.js dengan pointer-events: none
        particlesJS('particles-js', {
            particles: {
                number: {
                    value: 80,
                    density: {
                        enable: true,
                        value_area: 800
                    }
                },
                color: {
                    value: '#ffffff'
                },
                shape: {
                    type: 'circle'
                },
                opacity: {
                    value: 0.5,
                    random: false,
                    animation: {
                        enable: true,
                        speed: 1,
                        opacity_min: 0.1,
                        sync: false
                    }
                },
                size: {
                    value: 3,
                    random: true,
                    animation: {
                        enable: true,
                        speed: 2,
                        size_min: 0.1,
                        sync: false
                    }
                },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: '#ffffff',
                    opacity: 0.4,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 2,
                    direction: 'none',
                    random: false,
                    straight: false,
                    out_mode: 'out',
                    bounce: false,
                }
            },
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: {
                        enable: true,
                        mode: 'repulse'
                    },
                    onclick: {
                        enable: true,
                        mode: 'push'
                    },
                    resize: true
                },
                modes: {
                    repulse: {
                        distance: 100,
                        duration: 0.4
                    },
                    push: {
                        particles_nb: 4
                    }
                }
            },
            retina_detect: true
        });

        // Fungsi toggle password yang diperbaiki
        function togglePassword() {
            var passwordInput = document.getElementById('password');
            var toggleCheckbox = document.getElementById('toggleCheckbox');
            passwordInput.type = toggleCheckbox.checked ? 'text' : 'password';
        }

        // Event listener untuk form yang diperbaiki
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);
            fetch(this.action, {
                    method: this.method,
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: '<span style="color: #003366">Welcome</span>',
                            html: '<span style="color: #003366">Accessing Production Display System...</span>',
                            icon: 'success',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            backdrop: `rgba(0,51,102,0.4)`,
                            customClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            },
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        }).then(() => {
                            window.location.href = data.redirect_url;
                        });
                    } else {
                        Swal.fire({
                            title: '<span style="color: #003366">Access Denied</span>',
                            html: `<span style="color: #003366">${data.message || 'Invalid credentials. Please verify and try again.'}</span>`,
                            icon: 'error',
                            confirmButtonColor: '#ff5722',
                            confirmButtonText: '<i class="fas fa-redo"></i> Retry',
                            backdrop: `rgba(0,51,102,0.4)`,
                            customClass: {
                                popup: 'animate__animated animate__shakeX'
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: '<span style="color: #003366">System Notice</span>',
                        html: '<span style="color: #003366">Connection error. Please check your network and try again.</span>',
                        icon: 'warning',
                        confirmButtonColor: '#ff5722',
                        backdrop: `rgba(0,51,102,0.4)`,
                        customClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        }
                    });
                });
        });
    </script>
</body>

</html>