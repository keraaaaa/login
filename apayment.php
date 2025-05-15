<?php
session_start();
include("connect/connect.php");


if (isset($_POST['update_status'])) {
    $transaction_id = $_POST['transaction_id'];
    $status = $_POST['update_status'];  

    $sql_update = "UPDATE payments SET status = ? WHERE transaction_id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("si", $status, $transaction_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Status updated successfully.";
    } else {
    
        $_SESSION['message'] = "Error updating status: " . $stmt->error;
    }

    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit(); 
}

$sql_payments = "SELECT transaction_id, payment_date, payment_method, phone_number, term, status, bhouse_name FROM payments";
$result_payments = $conn->query($sql_payments);

$sql_transactions = "SELECT transaction_id, phone_number, term, mode_of_payment, payment_date, status, email FROM transactions";
$result_transactions = $conn->query($sql_transactions);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }

        h1, h2 {
            color: #333;
            display: inline-block;
            margin: 0;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #4CAF50;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        p {
            font-size: 16px;
            color: #555;
        }

        .btn {
            background-color:rgb(69, 92, 221);
            color: black;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            background-color:rgb(105, 129, 233);
        }

        .btn:focus {
            outline: none;
        }

        .btn-print {
            background-color:rgb(242, 255, 0);
            color:black;
            margin-left: 10px;
        }

        .btn-print:hover {
            background: rgb(245, 250, 153);
        }
        .btn-status {
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .btn-confirm {
            background-color: #4CAF50;
            color: black;
        }

        .btn-confirm:hover {
            background-color: #45a049;
        }

        .btn-renewal {
            background-color: #ffa500;
            color: black;
        }

        .btn-renewal:hover {
            background-color: #ff8c00;
        }

        @media print {
    body {
        background-color: white; 
    }

    .btn, .btn-print {
        display: none; 
    }

    table th, table td {
        padding: 10px; 
        font-size: 12px; 
        border: 1px solid #000; 
    }

    table th {
        background-color: #4CAF50;
        color: white;
    }

    table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    table tr:hover {
        background-color: transparent; 
    }

    p {
        font-size: 14px; 
    }

    .main-content {
        max-width: 100%; 
        margin: 0;
        padding: 10px;
        box-shadow:none;

    }
}
    </style>
   
</head>
<body>
    <div class="main-content">
        <div class="header-container">
            <h1>Payment Records</h1>
            <div>
                <button class="btn" onclick="window.location.href='stadmin.php'">Back</button>
                <button class="btn btn-print" onclick="printPage()">Print</button>
            </div>
        </div>

        <?php
        if (isset($_SESSION['message'])) {
            echo "<p style='color: green; font-weight: bold;'>" . $_SESSION['message'] . "</p>";
            unset($_SESSION['message']);
        }
        ?>

        <h2>Boarding House Owner Transaction</h2>

        <?php if ($result_payments->num_rows > 0): ?>
            <table border="1" cellpadding="10">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Payment Date</th>
                        <th>Payment Method</th>
                        <th>Phone Number</th>
                        <th>Term</th>
                        <th>Boarding House Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_payments->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['transaction_id']; ?></td>
                            <td><?php echo $row['payment_date']; ?></td>
                            <td><?php echo $row['payment_method']; ?></td>
                            <td><?php echo $row['phone_number']; ?></td>
                            <td><?php echo $row['term']; ?></td>
                            <td><?php echo $row['bhouse_name']; ?></td>
                            <td>
    <form method="POST" action="">
        <input type="hidden" name="transaction_id" value="<?php echo $row['transaction_id']; ?>">
        <button type="submit" name="update_status" value="Confirmed" class="btn-status btn-confirm">Confirm</button>
        <button type="submit" name="update_status" value="Renewal" class="btn-status btn-renewal">Renewal</button>
        <p>Status: <?php echo isset($row['status']) && $row['status'] != '' ? $row['status'] : 'Pending'; ?></p>
        </form>
</td>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No payment records found.</p>
        <?php endif; ?>

        <br><br>

        <h2>Students Transaction Records</h2>
        <?php if ($result_transactions->num_rows > 0): ?>
            <table border="1" cellpadding="10">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Payment Date</th>
                        <th>Payment Method</th>
                        <th>Phone Number</th>
                        <th>Term</th>
                        <th>Email</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_transactions->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['transaction_id']; ?></td>
                            <td><?php echo $row['payment_date']; ?></td>
                            <td><?php echo $row['mode_of_payment']; ?></td>
                            <td><?php echo $row['phone_number']; ?></td>
                            <td><?php echo $row['term']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No transaction records found.</p>
        <?php endif; ?>

        <?php $conn->close(); ?>
    </div>
    <script>
        function printPage() {
            window.print();
        }
    </script>
</body>
</html>
