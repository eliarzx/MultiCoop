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

$member_id = null;
$stmt = $conn->prepare("SELECT member_id FROM MEMBER_PROFILE WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($member_id);
$stmt->fetch();
$stmt->close();

if (!$member_id) {
    $error = "Member profile not found.";
}

$loans = [];
if ($member_id) {
    $stmt = $conn->prepare("
        SELECT
            la.loan_application_id,
            lt.name AS loan_type_name,
            la.amount_requested,
            la.term_months,
            la.status,
            lt.interest_rate
        FROM LOAN_APPLICATION la
        JOIN LOAN_TYPE lt ON la.loan_type_id = lt.loan_type_id
        WHERE la.member_id = ? AND la.status = 'Approved'
        ORDER BY la.application_date DESC
    ");
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $loan_application_id = $row['loan_application_id'];
        $amount_requested_loan = $row['amount_requested'];
        $interest_rate = $row['interest_rate'];
        $term_months = $row['term_months'];

        $total_interest_amount = 0;
        $total_amount_to_pay = $amount_requested_loan;

        if ($term_months > 0 && $interest_rate > 0) {
            $total_interest_amount = ($amount_requested_loan * ($interest_rate / 100) * ($term_months / 12));
            $total_amount_to_pay += $total_interest_amount;
        }

        $payments_made = 0;
        $payment_stmt = $conn->prepare("SELECT SUM(amount_paid) FROM LOAN_REPAYMENT WHERE loan_application_id = ?");
        $payment_stmt->bind_param("i", $loan_application_id);
        $payment_stmt->execute();
        $payment_stmt->bind_result($sum_paid);
        $payment_stmt->fetch();
        $payment_stmt->close();
        if ($sum_paid) {
            $payments_made = $sum_paid;
        }

        $remaining_balance = $total_amount_to_pay - $payments_made;

        if ($remaining_balance <= 0.01) {
            $update_status_stmt = $conn->prepare("UPDATE LOAN_APPLICATION SET status = 'Paid' WHERE loan_application_id = ? AND status != 'Paid'");
            $update_status_stmt->bind_param("i", $loan_application_id);
            $update_status_stmt->execute();
            $update_status_stmt->close();
            continue;
        }

        $monthly_installment = ($term_months > 0) ? ($total_amount_to_pay / $term_months) : 0;

        $last_payment_date = 'N/A';
        $last_payment_amount = 0;
        $last_payment_stmt = $conn->prepare("SELECT payment_date, amount_paid FROM LOAN_REPAYMENT WHERE loan_application_id = ? ORDER BY payment_date DESC LIMIT 1");
        $last_payment_stmt->bind_param("i", $loan_application_id);
        $last_payment_stmt->execute();
        $last_payment_stmt->bind_result($lp_date, $lp_amount);
        if ($last_payment_stmt->fetch()) {
            $last_payment_date = $lp_date;
            $last_payment_amount = $lp_amount;
        }
        $last_payment_stmt->close();

        $row['total_amount_to_pay'] = $total_amount_to_pay;
        $row['total_interest_amount'] = $total_interest_amount;
        $row['payments_made'] = $payments_made;
        $row['remaining_balance'] = $remaining_balance;
        $row['monthly_installment'] = $monthly_installment;
        $row['last_payment_date'] = $last_payment_date;
        $row['last_payment_amount'] = $last_payment_amount;

        $loans[] = $row;
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $loan_application_id = intval($_POST['loan_application_id']);
    $amount_paid = floatval($_POST['amount_paid']);
    $payment_date = date('Y-m-d H:i:s');
    $payment_method = "Cash";

    if ($amount_paid <= 0) {
        $error = "Payment amount must be greater than zero.";
    } else {
        $current_loan = null;
        foreach ($loans as $loan) {
            if ($loan['loan_application_id'] == $loan_application_id) {
                $current_loan = $loan;
                break;
            }
        }

        if ($current_loan) {
            if ($amount_paid > $current_loan['remaining_balance']) {
                $error = "Payment amount exceeds the remaining balance. Please enter " . number_format($current_loan['remaining_balance'], 2) . " or less.";
            } else {
                $stmt = $conn->prepare("INSERT INTO LOAN_REPAYMENT (loan_application_id, amount_paid, payment_date, payment_method) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("idss", $loan_application_id, $amount_paid, $payment_date, $payment_method);
                if ($stmt->execute()) {
                    $success = "Payment recorded successfully!";

                    $current_member_balance = 0;
                    $balance_stmt = $conn->prepare("SELECT balance FROM MEMBER_PROFILE WHERE member_id = ?");
                    $balance_stmt->bind_param("i", $member_id);
                    $balance_stmt->execute();
                    $balance_stmt->bind_result($current_member_balance);
                    $balance_stmt->fetch();
                    $balance_stmt->close();

                    $new_member_balance = $current_member_balance - $amount_paid;

                    $update_balance_stmt = $conn->prepare("UPDATE MEMBER_PROFILE SET balance = ? WHERE member_id = ?");
                    $update_balance_stmt->bind_param("di", $new_member_balance, $member_id);
                    $update_balance_stmt->execute();
                    $update_balance_stmt->close();

                    $loans = [];
                    if ($member_id) {
                        $stmt_re_fetch = $conn->prepare("
                            SELECT
                                la.loan_application_id,
                                lt.name AS loan_type_name,
                                la.amount_requested,
                                la.term_months,
                                la.status,
                                lt.interest_rate
                            FROM LOAN_APPLICATION la
                            JOIN LOAN_TYPE lt ON la.loan_type_id = lt.loan_type_id
                            WHERE la.member_id = ? AND la.status = 'Approved'
                            ORDER BY la.application_date DESC
                        ");
                        $stmt_re_fetch->bind_param("i", $member_id);
                        $stmt_re_fetch->execute();
                        $result_re_fetch = $stmt_re_fetch->get_result();
                        while ($row_re_fetch = $result_re_fetch->fetch_assoc()) {
                            $loan_application_id = $row_re_fetch['loan_application_id'];
                            $amount_requested_loan = $row_re_fetch['amount_requested'];
                            $interest_rate = $row_re_fetch['interest_rate'];
                            $term_months = $row_re_fetch['term_months'];

                            $total_interest_amount = 0;
                            $total_amount_to_pay = $amount_requested_loan;
                            if ($term_months > 0 && $interest_rate > 0) {
                                $total_interest_amount = ($amount_requested_loan * ($interest_rate / 100) * ($term_months / 12));
                                $total_amount_to_pay += $total_interest_amount;
                            }

                            $payments_made = 0;
                            $payment_stmt_re_fetch = $conn->prepare("SELECT SUM(amount_paid) FROM LOAN_REPAYMENT WHERE loan_application_id = ?");
                            $payment_stmt_re_fetch->bind_param("i", $loan_application_id);
                            $payment_stmt_re_fetch->execute();
                            $payment_stmt_re_fetch->bind_result($sum_paid_re_fetch);
                            $payment_stmt_re_fetch->fetch();
                            $payment_stmt_re_fetch->close();
                            if ($sum_paid_re_fetch) {
                                $payments_made = $sum_paid_re_fetch;
                            }

                            $remaining_balance = $total_amount_to_pay - $payments_made;

                            if ($remaining_balance <= 0.01) {
                                $update_status_stmt_re_fetch = $conn->prepare("UPDATE LOAN_APPLICATION SET status = 'Paid' WHERE loan_application_id = ? AND status != 'Paid'");
                                $update_status_stmt_re_fetch->bind_param("i", $loan_application_id);
                                $update_status_stmt_re_fetch->execute();
                                $update_status_stmt_re_fetch->close();
                            }

                            $monthly_installment = ($term_months > 0) ? ($total_amount_to_pay / $term_months) : 0;

                            $last_payment_date = 'N/A';
                            $last_payment_amount = 0;
                            $last_payment_stmt_re_fetch = $conn->prepare("SELECT payment_date, amount_paid FROM LOAN_REPAYMENT WHERE loan_application_id = ? ORDER BY payment_date DESC LIMIT 1");
                            $last_payment_stmt_re_fetch->bind_param("i", $loan_application_id);
                            $last_payment_stmt_re_fetch->execute();
                            $last_payment_stmt_re_fetch->bind_result($lp_date_re_fetch, $lp_amount_re_fetch);
                            if ($last_payment_stmt_re_fetch->fetch()) {
                                $last_payment_date = $lp_date_re_fetch;
                                $last_payment_amount = $lp_amount_re_fetch;
                            }
                            $last_payment_stmt_re_fetch->close();

                            $row_re_fetch['total_amount_to_pay'] = $total_amount_to_pay;
                            $row_re_fetch['total_interest_amount'] = $total_interest_amount;
                            $row_re_fetch['payments_made'] = $payments_made;
                            $row_re_fetch['remaining_balance'] = $remaining_balance;
                            $row_re_fetch['monthly_installment'] = $monthly_installment;
                            $row_re_fetch['last_payment_date'] = $last_payment_date;
                            $row_re_fetch['last_payment_amount'] = $last_payment_amount;

                            $loans[] = $row_re_fetch;
                        }
                        $stmt_re_fetch->close();
                    }


                    $notif = $conn->prepare("INSERT INTO NOTIFICATIONS (user_id, message) VALUES (?, ?)");
                    $msg = "You made a loan payment of PHP " . number_format($amount_paid, 2) . " on " . date("F d, Y h:i A") . " for Loan ID: " . $loan_application_id;
                    $notif->bind_param("is", $user_id, $msg);
                    $notif->execute();
                    $notif->close();

                } else {
                    $error = "Failed to record payment. Please try again.";
                }
                $stmt->close();
            }
        } else {
            $error = "Selected loan not found or not active.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="nav.css">
    <title>Repay Loans - UniHub Cooperative</title>
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

        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .loan-card {
            background-color: #e9ecef;
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 10px;
            align-items: center;
        }

        .loan-card strong {
            color: #003366;
            font-size: 0.9em;
            margin-right: 5px;
        }

        .loan-card .status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
            color: red;
            text-align: center;
            display: inline-block;
            min-width: 80px;
        }
        .loan-card .status.Approved {
            background-color: #28a745;
            color: white;
        }
        .loan-card .status.Pending {
            background-color: #ffc107;
            color: #333;
        }
        .loan-card .status.Rejected {
            background-color: #dc3545;
            color: white;
        }
        .loan-card .status.Paid {
            background-color: #6c757d;
            color: white;
        }
        .loan-actions {
            grid-column: 1 / -1;
            text-align: right;
            margin-top: 10px;
        }
        .loan-actions button {
            background-color: #003366;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .loan-actions button:hover {
            background-color: #0056b3;
        }

        .payment-form {
            background-color: #fff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
            display: none;
        }
        .payment-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .payment-form input[type="number"],
        .payment-form input[type="text"],
        .payment-form select {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        .payment-form button {
            background-color: #003366;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-right: 10px;
        }
        .payment-form button:hover {
            background-color: #218838;
        }
        .no-loans {
            text-align: center;
            padding: 20px;
            color: #555;
        }

        @media (max-width: 768px) {
            .loan-card {
                grid-template-columns: 1fr;
            }
            .loan-actions {
                text-align: center;
            }
            .navbar {
                flex-direction: column;
                height: auto;
                padding: 10px 0;
            }
            .navbar li {
                width: 100%;
                text-align: center;
            }
            .navbar li:first-child a {
                margin-right: 0;
                padding-left: 16px;
            }
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
        <h1>Repayment Schedule & Details</h1>
        <p>Let’s stay on track! Repay your loan on time and unlock more deals with UniHub.</p>
    </div>
</section>

    <div class="container">
        <h1>My Active Loans</h1>

        <?php if ($success): ?>
            <div class="message success"><?= $success ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>

        <?php if (empty($loans)): ?>
            <p class="no-loans">You currently have no active or pending loan applications.</p>
        <?php else: ?>
            <?php foreach ($loans as $loan): ?>
                <div class="loan-card">
                    <div><strong>Loan ID:</strong> <?= $loan['loan_application_id'] ?></div>
                    <div><strong>Loan Type:</strong> <?= $loan['loan_type_name'] ?></div>
                    <div><strong>Principal Amount:</strong> ₱<?= number_format($loan['amount_requested'], 2) ?></div>
                    <div><strong>Total Interest Amount:</strong> ₱<?= number_format($loan['total_interest_amount'], 2) ?></div>
                    <div><strong>Total Payable:</strong> ₱<?= number_format($loan['total_amount_to_pay'], 2) ?></div>
                    <div><strong>Monthly Installment:</strong> ₱<?= number_format($loan['monthly_installment'], 2) ?></div>
                    <div><strong>Payments Made:</strong> ₱<?= number_format($loan['payments_made'], 2) ?></div>
                    <div><strong>Remaining Balance:</strong> ₱<?= number_format($loan['remaining_balance'], 2) ?></div>
                    <div><strong>Term:</strong> <?= $loan['term_months'] ?> months</div>
                    <div><strong>Status:</strong> <span class="status <?= $loan['status'] ?>"><?= $loan['status'] ?></span></div>
                    <div><strong>Last Payment Date:</strong> <?= $loan['last_payment_date'] ?></div>
                    <div><strong>Last Payment Amount:</strong> ₱<?= number_format($loan['last_payment_amount'], 2) ?></div>
                    <div class="loan-actions">
                        <button class="pay-btn"
                            data-id="<?= $loan['loan_application_id'] ?>"
                            data-remaining="<?= $loan['remaining_balance'] ?>"
                            data-installment="<?= number_format($loan['monthly_installment'], 2, '.', '') ?>">
                            Make Payment
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div id="paymentForm" class="payment-form">
            <h2>Make a Payment</h2>
            <form action="loan_Repay.php" method="POST"> <input type="hidden" id="form-loan-id" name="loan_application_id">
                <label for="amount_paid">Amount to Pay:</label>
                <input type="number" id="amount_paid" name="amount_paid" step="0.01" min="0.01" required>
                <label for="payment_method">Payment Method:</label>
                <input type="text" id="payment_method" name="payment_method" value="Cash" readonly>
                <button type="submit">Submit Payment</button>
                <button type="button" id="cancelPaymentBtn" style="background-color: #6c757d;">Cancel</button>
            </form>
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
        document.addEventListener('DOMContentLoaded', function() {
            const payButtons = document.querySelectorAll('.pay-btn');
            const paymentForm = document.getElementById('paymentForm');
            const formLoanId = document.getElementById('form-loan-id');
            const amountPaidInput = document.getElementById('amount_paid');
            const cancelPaymentBtn = document.getElementById('cancelPaymentBtn');

            payButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const loanId = this.dataset.id;
                    const remainingBalance = parseFloat(this.dataset.remaining);
                    const monthlyInstallment = parseFloat(this.dataset.installment);

                    formLoanId.value = loanId;
                    amountPaidInput.value = monthlyInstallment.toFixed(2);
                    amountPaidInput.max = remainingBalance.toFixed(2);

                    paymentForm.style.display = 'block';
                    window.scrollTo({
                        top: paymentForm.offsetTop - 50,
                        behavior: 'smooth'
                    });
                });
            });

            cancelPaymentBtn.addEventListener('click', function() {
                paymentForm.style.display = 'none';
                amountPaidInput.value = '';
                formLoanId.value = '';
            });

            const messages = document.querySelectorAll('.message');
            messages.forEach(message => {
                setTimeout(() => {
                    message.style.display = 'none';
                }, 5000);
            });
        });
    </script>
</body>
</html>
