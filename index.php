<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cooperative Multipurpose</title>
    <link rel="stylesheet" href="nav.css">
    <style>
        <style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        overflow-x: hidden;
        background: #F4F1E1;
    }

    .hero {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 130px 10% 100px;
        background: linear-gradient(to bottom, #F4F1E1, white);
        animation: fadeIn 1s ease-out;
        gap: 40px;
        flex-wrap: wrap;
    }

    .hero-text {
        max-width: 500px;
        animation: slideInLeft 1s ease-out;
    }

    .hero-text h1 {
        font-size: 48px;
        color: #003366;
        margin-bottom: 20px;
    }

    .hero-text p {
        font-size: 18px;
        color: #333;
        margin-bottom: 30px;
    }

    .hero-text button {
        padding: 12px 25px;
        font-size: 18px;
        background-color: #D6C4A1;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: transform 0.3s, background-color 0.3s;
    }

    .hero-text button:hover {
        background-color: #bda57c;
        transform: scale(1.05);
    }

    .hero-image {
        flex: 1;
        background: url('hero_image.svg') no-repeat center center;
        background-size: contain;
        height: 300px;
        animation: slideInRight 1.5s ease-out;
    }

    .services {
        background-color: white;
        padding: 60px 10%;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 30px;
        opacity: 0;
        transform: translateY(50px);
        transition: all 1s ease-out;
    }

    .services.reveal {
        opacity: 1;
        transform: translateY(0);
    }

    .service-box {
        background-color: #F4F1E1;
        border-radius: 10px;
        padding: 30px;
        flex: 1;
        min-width: 250px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: transform 0.3s;
    }

    .service-box:hover {
        transform: translateY(-10px);
    }

    .service-box h3 {
        color: #003366;
        margin-bottom: 10px;
    }

    .service-box p {
        color: #555;
    }

    .about {
        padding: 60px 10%;
        background-color: #D6C4A1;
        color: white;
        text-align: center;
        animation: fadeIn 2s ease-in;
    }

    .about h2 {
        font-size: 36px;
        margin-bottom: 20px;
    }

    .about p {
        font-size: 18px;
        max-width: 800px;
        margin: 0 auto;
    }

    footer {
        background-color: #003366;
        color: white;
        padding: 20px 10%;
        text-align: center;
    }

    /* Animations */
    @keyframes slideInLeft {
        from { transform: translateX(-50px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes slideInRight {
        from { transform: translateX(50px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @media (max-width: 768px) {
        .hero {
            flex-direction: column;
            padding: 120px 5% 60px;
        }

        .hero-image {
            height: 200px;
            width: 100%;
        }

        .services {
            flex-direction: column;
        }
    }
</style>

    </style>
</head>
<body>

    <ul class="navbar">
        <li><a href="index.php">UNIHUB</a></li>
        <li><a href="loginPage.php">LOGIN</a></li>
        <li><a href="about_Us2.php">ABOUT US</a></li>
        <li><a href="contact_Us2.php">CONTACT US</a></li>
    </ul>

    <section class="hero">
        <div class="hero-text">
            <h1>UniHub Cooperative</h1>
            <p>Empowering communities with trasparency, smart loans, and genuine support — UnityHub Multipurpose Cooperative is your trusted partner in building a better and brighter future.</p>
            <button onclick="window.location.href='member.php';">Join Us!</button>
        </div>
        <div class="hero-image"></div>
    </section>

    <section class="services">
    <div class="service-box">
        <h3>Loan Services</h3>
        <p>Affordable and flexible loans tailored to your needs — easy, transparent, and reliable.</p>
    </div>

    <div class="service-box">
        <h3>Savings Program</h3>
        <p>Secure your future with flexible savings options and various member benefits.</p>
    </div>

    <div class="service-box">
        <h3>Payment Tracking</h3>
        <p>We care about your credit! Track your upcoming payments and never miss a due date!</p>
    </div>

    <div class="service-box">
        <h3>Financial Reporting</h3>
        <p>Easy access to detailed financial reports to keep track of your account. Stay informed about your contributions, loans, and payment history at all times.</p>
    </div>

    <div class="service-box">
        <h3>Secure Data Handling</h3>
        <p>Financial and personal information encryption unified with top-tier security protocols. Rest assure because in UnityHub, you are safe!</p>
    </div>
    
</section>

    <section class="about">
        <h2>About Our Multipurpose Cooperative</h2>
        <p>Founded with the mission to unify financial literacy and lives through mutual assistance, UniHub Cooperative stands strong with years of service to the community. We believe in trust, transparency, and togetherness.</p>
    </section>

    <footer>
        &copy; 2025 UniHub Cooperative | Malolos, Bulacan | 0968-890-6796 | deguzmankarell.infinityfreeapp.com
    </footer>

</body>
<script>
    // Reveal .services on scroll
    window.addEventListener('scroll', function () {
        const services = document.querySelector('.services');
        const rect = services.getBoundingClientRect();
        if (rect.top <= window.innerHeight - 100) {
            services.classList.add('reveal');
        }
    });
</script>
</html>