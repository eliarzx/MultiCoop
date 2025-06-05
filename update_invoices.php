<?php
session_start();
include 'db_connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

// Handle "Mark as Paid" form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_paid'], $_POST['invoice_id'])) {
    $invoice_id = $_POST['invoice_id'];

    $update_paid_sql = "UPDATE INVOICES SET status = 'paid' WHERE invoice_id = ?";
    $stmt = $conn->prepare($update_paid_sql);
    $stmt->bind_param('i', $invoice_id);
    if ($stmt->execute()) {
        echo "<script>alert('Invoice marked as paid.'); window.location.href = window.location.href;</script>";
        exit();
    } else {
        echo "<script>alert('Failed to update invoice status.');</script>";
    }
    $stmt->close();
}

// Automatically update overdue invoices
$today = date('Y-m-d');
$update_overdue_sql = "UPDATE INVOICES 
                       SET status = 'overdue' 
                       WHERE due_date < ? AND status = 'unpaid'";

$stmt = $conn->prepare($update_overdue_sql);
$stmt->bind_param('s', $today);
$stmt->execute();
$stmt->close();

// Fetch all invoices with user names
$sql = "SELECT i.invoice_id, u.first_name, u.last_name, i.description, i.amount, i.due_date, i.status
        FROM INVOICES i
        JOIN USERS u ON i.user_id = u.user_id
        ORDER BY i.due_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>All Invoices</title>
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
/>
<style>
    nav {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background-color: #00796b;
        display: flex;
        justify-content: center;
        gap: 40px;
        padding: 14px 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        z-index: 1000;
    }
    nav a {
        color: #e0f2f1;
        font-weight: 600;
        font-size: 1.1rem;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 6px;
        transition: background-color 0.3s ease;
    }
    nav a:hover {
        background-color: #004d40;
        color: #a7ffeb;
    }
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f8;
        padding: 20px;
    }
    h1 {
        color: #00796b;
        margin-top: 100px;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 3px 8px rgba(0,0,0,0.15);
    }
    th, td {
        padding: 14px 20px;
        text-align: left;
        border-bottom: 1px solid #e1e1e1;
        font-size: 16px;
    }
    th {
        background-color: #00796b;
        color: #fff;
        font-weight: 600;
    }
    tr:hover {
        background-color: #f1f9f8;
    }
    .mark-paid-btn {
        background-color: #26a69a;
        color: white;
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }
    .mark-paid-btn:hover {
        background-color: #00796b;
    }
    .no-data {
        text-align: center;
        padding: 40px 0;
        color: #777;
        font-size: 18px;
    }
</style>
</head>
<body>

<nav>
    <a href="accountant.php"><i class="fas fa-home"></i> Home</a>
    <a href="add_invoice.php"><i class="fas fa-file-invoice"></i> Manage</a>
</nav>

<h1>All Invoices</h1>

<?php if ($result && $result->num_rows > 0): ?>
<table>
    <thead>
        <tr>
            <th>Invoice ID</th>
            <th>User</th>
            <th>Description</th>
            <th>Amount (₱)</th>
            <th>Due Date</th>
            <th>Status</th>
            <th>Update Status</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($invoice = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($invoice['invoice_id']) ?></td>
            <td><?= htmlspecialchars($invoice['first_name'] . ' ' . $invoice['last_name']) ?></td>
            <td><?= htmlspecialchars($invoice['description']) ?></td>
            <td><?= number_format($invoice['amount'], 2) ?></td>
            <td><?= htmlspecialchars($invoice['due_date']) ?></td>
            <td><?= ucfirst(htmlspecialchars($invoice['status'])) ?></td>
            <td>
                <?php if ($invoice['status'] !== 'paid'): ?>
                    <form method="POST" style="margin:0;">
                        <input type="hidden" name="invoice_id" value="<?= htmlspecialchars($invoice['invoice_id']) ?>">
                        <button type="submit" name="mark_paid" class="mark-paid-btn">Mark as Paid</button>
                    </form>
                <?php else: ?>
                    <span style="color: green; font-weight: bold;">Paid</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php else: ?>
<p class="no-data">No invoices found.</p>
<?php endif; ?>

</body>
</html>