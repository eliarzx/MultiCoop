<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Loading | UNIHUB COOPERATIVE</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --navy: #003366;
      --yellowish: #D6C4A1;
      --gray: #B0B0B0;
      --burgundy: #800020;
      --cream: #F4F1E1;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Segoe UI", sans-serif;
    }

    html, body {
      height: 100%;
      background: linear-gradient(to bottom right, #002244, #003366);
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .loading-container {
      background-color: rgba(176, 176, 176, 0.1);
      border: 1px solid var(--gray);
      width: 400px;
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
      animation: fadeIn 0.6s ease-in-out;
      text-align: center;
    }

    @keyframes fadeIn {
      from {
        transform: translateY(30px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .admin-icon {
      font-size: 48px;
      color: var(--yellowish);
      margin-bottom: 20px;
    }

    h2 {
      color: var(--yellowish);
      margin-bottom: 10px;
    }

    .loading-text {
      color: var(--cream);
      font-size: 16px;
      margin-top: 20px;
      height: 24px;
      transition: opacity 0.3s ease-in-out;
    }

    .spinner {
      margin-top: 30px;
      width: 40px;
      height: 40px;
      border: 4px solid var(--cream);
      border-top: 4px solid var(--burgundy);
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-left: auto;
      margin-right: auto;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }
  </style>
</head>
<body>

  <div class="loading-container">
    <div class="admin-icon">
      <i class="fas fa-user-shield"></i>
    </div>
    <h2>Preparing Dashboard...</h2>
    <div class="spinner"></div>
    <div class="loading-text" id="loading-text">Initializing system</div>
  </div>

  <script>
    const texts = [
      "Initializing system",
      "Authenticating access",
      "Loading secure dashboard",
      "Preparing the admin panel",
      "Please wait..."
    ];

    let i = 0;
    const textElement = document.getElementById('loading-text');

    const interval = setInterval(() => {
      i++;
      if (i < texts.length) {
        textElement.style.opacity = 0;
        setTimeout(() => {
          textElement.textContent = texts[i];
          textElement.style.opacity = 1;
        }, 300);
      } else {
        clearInterval(interval);
        window.location.href = "adminPanel.php";
      }
    }, 1500);
  </script>

</body>
</html>