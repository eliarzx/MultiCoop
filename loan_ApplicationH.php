<?php
include 'db_connect.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$success = "";
$error = "";
$active_application = false;

// Get member_id from user_id
$stmt = $conn->prepare("SELECT member_id FROM MEMBER_PROFILE WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($member_id);
$stmt->fetch();
$stmt->close();

// Check for active pending loan application
$checkApp = $conn->prepare("SELECT COUNT(*) FROM LOAN_APPLICATION WHERE member_id = ? AND status = 'Pending'");
$checkApp->bind_param("i", $member_id);
$checkApp->execute();
$checkApp->bind_result($count);
$checkApp->fetch();
$checkApp->close();

if ($count > 0) {
    $active_application = true;
}

// Fetch loan types for the form
$loanTypes = [];
$result = $conn->query("SELECT loan_type_id, name, interest_rate, interest_type FROM LOAN_TYPE");
while ($row = $result->fetch_assoc()) {
    $loanTypes[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$active_application) {
    $loan_type_id = $_POST['loan_type_id'];
    $principal_amount_requested = floatval($_POST['amount_requested']); // This is the principal
    $term_months = intval($_POST['term_months']);
    $notes = trim($_POST['notes']);
    $application_date = date('Y-m-d H:i:s');
    $status = "Pending"; // Loan is pending approval initially

    if ($principal_amount_requested > 0 && $term_months > 0) {
        // Fetch the interest rate for the selected loan type (server-side calculation)
        $stmt_interest = $conn->prepare("SELECT interest_rate FROM LOAN_TYPE WHERE loan_type_id = ?");
        $stmt_interest->bind_param("i", $loan_type_id);
        $stmt_interest->execute();
        $stmt_interest->bind_result($interest_rate);
        $stmt_interest->fetch();
        $stmt_interest->close();

        // Calculate the total amount including flat interest
        // This value will be stored in LOAN_APPLICATION.amount_requested
        $total_amount_with_interest = $principal_amount_requested * (1 + ($interest_rate / 100));

        // Start a transaction for atomicity for the application submission and notification
        $conn->begin_transaction();

        try {
            // 1. Insert into LOAN_APPLICATION table
            $stmt = $conn->prepare("INSERT INTO LOAN_APPLICATION
                (member_id, loan_type_id, amount_requested, term_months, application_date, status, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?)");
            // Store total_amount_with_interest in amount_requested (as the amount to be repaid)
            $stmt->bind_param("iidisss", $member_id, $loan_type_id, $total_amount_with_interest, $term_months, $application_date, $status, $notes);

            if (!$stmt->execute()) {
                throw new Exception("Failed to submit loan application.");
            }
            $stmt->close();

            // 2. Insert into NOTIFICATIONS table
            $notif = $conn->prepare("INSERT INTO NOTIFICATIONS (user_id, message) VALUES (?, ?)");
            $msg = "You submitted a loan application for PHP " . number_format($principal_amount_requested, 2) . " (Estimated Total Repayment: PHP " . number_format($total_amount_with_interest, 2) . ") on " . date("F d, Y h:i A") . ". It is now pending approval.";
            $notif->bind_param("is", $user_id, $msg);

            if (!$notif->execute()) {
                throw new Exception("Failed to create notification.");
            }
            $notif->close();

            // If all operations are successful, commit the transaction
            $conn->commit();
            $success = "Loan application submitted successfully! It is now pending approval. Estimated total repayment: PHP " . number_format($total_amount_with_interest, 2);
            $active_application = true;

        } catch (Exception $e) {
            // If any operation fails, rollback the transaction
            $conn->rollback();
            $error = "Transaction failed: " . $e->getMessage() . " Please try again.";
        }

    } else {
        $error = "Enter valid loan details.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Apply for Loan - UniHub Cooperative</title>
<link rel="stylesheet" href="nav.css">
<style>
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(45deg, #F4F1E1, #D6C4A1);
        background-size: 400% 400%;
        animation: gradientMove 15s ease infinite;
        margin: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        color: #003366;
    }

    @keyframes gradientMove {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .wrapper {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 20px;
        box-sizing: border-box;
        width: 100%;
    }

    .hero {
        background-color: #003366;
        color: #F4F1E1;
        text-align: center;
        padding: 60px 20px 40px 20px;
        margin-top: 50px;
        width: 100%;
        box-sizing: border-box;
        transition: transform 0.3s ease;
    }

    .hero:hover {
        transform: scale(1.02);
    }

    .hero h1 {
        font-size: 34px;
        margin-bottom: 10px;
    }

    .hero p {
        font-size: 22px;
    }

    .container {
        background: #F4F1E1;
        width: 90%;
        max-width: 800px;
        margin: 40px auto;
        border-radius: 8px;
        padding: 40px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }


    .container h1 {
        text-align: center;
        color: #800020;
        margin-bottom: 20px;
        font-size: 32px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: bold;
        display: block;
        margin-bottom: 8px;
    }

    input[type="text"],
    input[type="range"],
    select,
    textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 16px;
        box-sizing: border-box;
    }

    .slider-display {
        text-align: center;
        font-weight: bold;
        margin-top: 10px;
        color: #003366;
    }

    .apply-btn {
        width: 100%;
        padding: 14px;
        background-color: #003366;
        color: #F4F1E1;
        font-size: 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .apply-btn:hover {
        background-color: #800020;
    }

    .info-section {
        max-width: 1000px;
        margin: 40px auto;
        padding: 0 20px;
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .info-card {
        background: #F4F1E1;
        padding: 20px;
        border-left: 5px solid #003366;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        flex: 1 1 250px;
        transition: all 0.3s ease;
    }

    .info-card:hover {
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        transform: scale(1.01);
    }

    .info-card h3 {
        margin-top: 0;
        color: #800020;
        font-size: 18px;
    }

    footer {
        width: 100%;
        background: #003366;
        color: #F4F1E1;
        padding: 20px 0;
        margin-top: auto;
    }

    footer .wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    footer .contact, footer .social {
        flex: 1;
        margin: 5px 0;
    }

    footer .social {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    footer .social span {
        font-size: 20px;
    }
</style>
</head>
<body>

<ul class="navbar">
    <li><a href="loanAccount.php">HOME</a></li>
    <li><a href="profile_Template2.php">MY PROFILE</a></li>
    <li><a href="#">NOTIFICATION</a></li>
    <li><a href="helpMembs2.php">HELP</a></li>
    <li><a href="index.php">LOG OUT</a></li>
</ul>

<section class="hero">
    <div class="wrapper">
        <h1>Apply for a Loan</h1>
        <p>Let’s get you closer to your dreams</p>
    </div>
</section>

<div class="container">
    <h1>Loan Application Form</h1>
    <form method="post" action="">
        <div class="form-group">
            <label for="loan-amount">Loan Amount (Principal):</label>
            <input type="range" id="loan-amount" name="amount_requested" min="1000" max="250000" value="10000">
            <div class="slider-display" id="loan-value">PHP 10,000</div>
        </div>

        <div class="form-group">
            <label for="loan-purpose">Purpose of Loan:</label>
            <select id="loan-purpose" name="loan_type_id">
                <?php foreach ($loanTypes as $type): ?>
                    <option value="<?php echo htmlspecialchars($type['loan_type_id']); ?>"
                            data-interest-rate="<?php echo htmlspecialchars($type['interest_rate']); ?>">
                        <?php echo htmlspecialchars($type['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="terms">Loan Terms:</label>
            <select id="terms" name="term_months">
                <option value="6">6 Months</option>
                <option value="12">12 Months</option>
                <option value="24">24 Months</option>
            </select>
        </div>

        <div class="form-group">
            <label for="total-repayment">Estimated Total Repayment (Principal + Interest):</label>
            <div class="slider-display" id="total-repayment">PHP 0.00</div>
        </div>

        <div class="form-group">
            <label for="notes">Notes / Comments:</label>
            <textarea id="notes" name="notes" rows="3" placeholder="Add any additional notes..."></textarea>
        </div>

        <?php if ($success): ?>
            <p style="color: green; text-align: center;"><?php echo $success; ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p style="color: red; text-align: center;"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if ($active_application): ?>
            <p style="color: blue; text-align: center;">You have an active loan application pending. You cannot submit another one at this time.</p>
        <?php else: ?>
            <button class="apply-btn" type="submit">Apply for Loan</button>
        <?php endif; ?>
    </form>
</div>

<div class="info-section">
    <div class="info-card">
        <h3>Quick Approval</h3>
        <p>Up to PHP 250,000 with flexible terms</p>
    </div>
    <div class="info-card">
        <h3>Simple Process</h3>
        <p>Minimal paperwork and hassle-free application</p>
    </div>
    <div class="info-card">
        <h3>Trusted Cooperative</h3>
        <p>Helping you every step of the way</p>
    </div>
</div>

<footer>
    <div class="wrapper">
        <div class="contact">
            <p>Contact</p>
            <p>3000 Malolos, Bulacan, Philippines</p>
        </div>
        <div class="social">
            <p>Connect</p>
            <span>● ● ●</span>
        </div>
    </div>
</footer>

<script>
    const loanAmountSlider = document.getElementById('loan-amount');
    const loanValueDisplay = document.getElementById('loan-value');
    const loanPurposeSelect = document.getElementById('loan-purpose');
    const termsSelect = document.getElementById('terms');
    const totalRepaymentDisplay = document.getElementById('total-repayment');

    function calculateTotalRepayment() {
        const principal = parseFloat(loanAmountSlider.value);
        const selectedOption = loanPurposeSelect.options[loanPurposeSelect.selectedIndex];
        // Ensure data-interest-rate exists before trying to access it
        const interestRate = parseFloat(selectedOption.dataset.interestRate || 0) / 100; // Convert percentage to decimal, default to 0 if not set

        const totalRepayment = principal * (1 + interestRate);
        totalRepaymentDisplay.textContent = `PHP ${totalRepayment.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })}`;
    }

    loanAmountSlider.addEventListener('input', function () {
        const formatted = parseInt(this.value).toLocaleString();
        loanValueDisplay.textContent = `PHP ${formatted}`;
        calculateTotalRepayment();
    });

    loanPurposeSelect.addEventListener('change', calculateTotalRepayment);
    termsSelect.addEventListener('change', calculateTotalRepayment);

    // Initial calculation when the page loads
    document.addEventListener('DOMContentLoaded', calculateTotalRepayment);
</script>

</body>
</html>