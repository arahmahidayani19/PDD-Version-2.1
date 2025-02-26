<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pdd";

$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Hitung jumlah unik line_name
$sql_lines = "SELECT COUNT(DISTINCT line_name) AS total_lines FROM lines_machines";
$result_lines = $conn->query($sql_lines);
$row_lines = $result_lines->fetch_assoc();
$total_lines = $row_lines['total_lines'];

// Hitung jumlah total machine_name
$sql_machines = "SELECT COUNT(machine_name) AS total_machines FROM lines_machines";
$result_machines = $conn->query($sql_machines);
$row_machines = $result_machines->fetch_assoc();
$total_machines = $row_machines['total_machines'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>PDD - Production Display</title>

  <link href="css.css" rel="stylesheet">
  <link href="all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html,
    body {
      height: 100%;
      overflow: hidden;
      font-family: 'Open Sans', sans-serif;
      color: #333;
    }

    @media (max-width: 768px) {

      html,
      body {
        overflow-y: auto;
      }
    }

    #particles-js {
      position: fixed;
      width: 100%;
      height: 100%;
      background: linear-gradient(to bottom right, #003366, #0055a5);
      z-index: 1;
    }

    #header {
      background: rgba(0, 51, 102, 0.95);
      padding: 10px 0;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
      position: relative;
      z-index: 2;
      height: 10vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    #header img {
      max-height: 8vh;
      animation: pulse 2s infinite;
    }

    #hero {
      position: relative;
      color: white;
      text-align: center;
      z-index: 2;
      height: 80vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 20px;
    }

    #hero h1 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 20px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
      animation: slideIn 1s ease-out;
    }

    .stats-container {
      display: flex;
      justify-content: center;
      gap: 30px;
      margin: 20px 0;
      flex-wrap: wrap;
    }

    .stat-box {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      padding: 15px;
      border-radius: 10px;
      width: 180px;
      transition: transform 0.3s;
    }

    .stat-box i {
      font-size: 1.8rem;
      color: #ff5722;
    }

    .stat-box h3 {
      margin: 8px 0;
      font-size: 1.6rem;
    }

    .stat-box p {
      margin: 0;
      font-size: 0.9rem;
      opacity: 0.8;
    }

    .marquee-container {
      overflow: hidden;
      white-space: nowrap;
      margin: 20px auto;
      width: 100%;
      max-width: 800px;
      background: rgba(255, 255, 255, 0.1);
      padding: 12px;
      border-radius: 10px;
      backdrop-filter: blur(5px);
    }

    .marquee-text {
      display: inline-block;
      animation: marquee 20s linear infinite;
      color: #fff;
      font-size: 1rem;
    }

    .machine-status {
      padding: 15px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 10px;
      backdrop-filter: blur(5px);
      margin: 20px auto;
      max-width: 800px;
    }

    .btn-primary {
      background: #ff5722;
      border: none;
      color: #fff;
      font-weight: 600;
      padding: 12px 25px;
      border-radius: 30px;
      text-transform: uppercase;
      transition: all 0.3s;
      text-decoration: none;
      display: inline-block;
      margin-top: 15px;
    }

    .btn-primary:hover {
      background: #e64a19;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(230, 74, 25, 0.4);
    }

    #footer {
      background: rgba(0, 51, 102, 0.95);
      color: #fff;
      text-align: center;
      padding: 10px 0;
      position: relative;
      z-index: 2;
      height: 10vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .status-indicator {
      display: inline-block;
      width: 8px;
      height: 8px;
      border-radius: 50%;
      margin-right: 10px;
      animation: blink 2s infinite;
      background-color: #4CAF50;
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

    @keyframes slideIn {
      from {
        transform: translateY(-50px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    @keyframes marquee {
      0% {
        transform: translateX(100%);
      }

      100% {
        transform: translateX(-100%);
      }
    }

    @keyframes blink {
      0% {
        opacity: 1;
      }

      50% {
        opacity: 0.4;
      }

      100% {
        opacity: 1;
      }
    }

    @media (max-width: 768px) {
      #header {
        height: auto;
        padding: 10px 0;
      }

      #hero {
        height: auto;
        padding: 40px 20px;
      }

      #footer {
        height: auto;
      }

      .stats-container {
        gap: 15px;
      }

      .stat-box {
        width: 150px;
        padding: 10px;
      }

      #hero h1 {
        font-size: 2rem;
      }
    }

    @media (max-width: 480px) {
      .stats-container {
        flex-direction: column;
        align-items: center;
      }

      .stat-box {
        width: 80%;
      }
    }
  </style>
</head>

<body>
  <div id="particles-js"></div>

  <header id="header">
    <img src="dist/img/sanwa.png" alt="Sanwa Logo">
  </header>

  <section id="hero">
    <div class="container">
      <h1>PRODUCTION DISPLAY</h1>

      <div class="stats-container">
        <div class="stat-box">
          <i class="fas fa-cogs"></i>
          <h3><?php echo $total_machines; ?></h3>
          <p>📌 Currently, there are <?php echo $total_machines; ?> machines!</p>
        </div>
        <div class="stat-box">
          <i class="fas fa-network-wired"></i>
          <h3><?php echo $total_lines; ?></h3>
          <p>🔗 <?php echo $total_lines; ?> production lines!</p>
        </div>
      </div>

      <div class="marquee-container">
        <div class="marquee-text">
          INFORMATION SYSTEM APPLICATION ON EVERY MACHINE AT PT SANWA ENGINEERING BATAM
        </div>
      </div>

      <a href="login/login.php" class="btn-primary">
        <i class="fas fa-sign-in-alt"></i> Login to Dashboard
      </a>
    </div>
  </section>

  <footer id="footer">
    <p>&copy; 2024 PT Sanwa Engineering Batam. All rights reserved.</p>
  </footer>

  <script src="particles.min.js"></script>
  <script>
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
  </script>
</body>

</html>