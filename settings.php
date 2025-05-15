<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style2.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
<style>
     .btn {
          
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
            background:rgb(242, 255, 0);
            margin-left: 10px;
        }

        .btn-print:hover {
            background: rgb(245, 250, 153);
        }
        .tables-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        table {
            width: 105%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }
        table thead th {
            padding: 1rem 0rem;
            text-align: center;
            font-size: 1rem;
        }
        table tbody td {
            padding: 1rem .5rem;
            text-align:center;
        }

        table tbody td:first-child {
            padding-left: 1rem;
            color: black;
            font-weight: 300;
            font-size: .9rem;
        }

        table tbody tr{
            border-bottom: 1px solid #dee2e8;
            background-color:lightgrey ;
        }
@media print {
    .sidebar, .download-button, .buttons, td:first-child, th:first-child, td:last-child, th:last-child, .btn {
        display: none;  
    }
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .main-content {
        width: 100%;
        margin: 0 auto;
        text-align: left; 
    }
   
    .records {
        width: 100%;
        margin: 0 auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px auto;
        padding: 50px;
    }

    th, td {
        border: 1px solid #000;
        padding: 15px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }
}

.button {
    display: inline-block;
    padding: 10px 20px;
    font-size: 14px;
    text-align: center;
    text-decoration: none;
    border-radius: 5px;
    color: black;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

.update-button {
    background-color: #4CAF50; 
}

.update-button:hover {
    background-color: #45a049;
}

.delete-button {
    background-color: #f44336; 
}

.delete-button:hover {
    background-color: #da190b;
}

td {
    text-align: center;
}

.page-header {
    width:105%;
    padding: 1.3rem 1rem;
    background: #E9edf2;
    border-bottom:none;
}
</style>
</head>
<body>
    
    <div class="sidebar">    
        <div class="side-content">
            <div class="profile">
                <div class="profile-img bg-img" style="background-image: url(bckgnds/m.jpg)"></div>
                <h4>ADMIN</h4>
                <small>Admin</small>
            </div>
            <div class="side-menu">
                <ul>
                    <li>
                       <a href="stadmin.php">
                            <span class="las la-home"></span>
                            <small>Dashboard</small>
                        </a>
                    </li>
                    <li>
                       <a href="settings.php" class="active">
                            <span class="las la-user-graduate"></span>
                            <small>Student Log</small>
                        </a>
                    </li>
                    <li>
                       <a href="home_owner.php">
                            <span class="las la-user-tie"></span>
                            <small>Home Owner</small>
                        </a>
                    </li>
                    <li>
                    <a href="roomup.php">
                    <span class="las la-bed"></span>
                        <small>Rooms</small>
                    </a>
                    <li>
                    <a href="https://accounts.google.com/v3/signin/challenge/pwd?TL=AO-GBTedLDDkN4svV-Dlq9F1nZQDfLwiNooNQknDF6kkC4cnXb6_dL8hb-BMaTBN&checkConnection=youtube%3A1495&checkedDomains=youtube&cid=1&continue=https%3A%2F%2Fmail.google.com%2Fmail%2Fu%2F0%2F&ddm=1&emr=1&flowName=GlifWebSignIn&followup=https%3A%2F%2Fmail.google.com%2Fmail%2Fu%2F0%2F&hl=fil&ifkv=AVdkyDl5Ldznok96sg7mRZVi9qGN_5Nb3HJ9Cj_rdRWWlr-I4YXbTgwsM9Ft3h8DktUlaIpYe9U2dQ&osid=1&pstMsg=1&service=mail">
                    <span class="las la-envelope"></span>
                            <small>Mailbox</small>
                        </a>
                    </li>
                    <li>
                    <a href="apayment.php">
                        <span class="las la-file-invoice-dollar"></span><small>Payment</small>
                     </a>
                </li>
                    <li>
                       <a href="auth/logout.php">
                            <span class="las la-power-off"></span>
                            <small>Logout</small>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="main-content">
        <main>       
            <div class="page-header">
                <h1>Student Logged in Dashboard</h1>
                <button class="btn btn-print" onclick="printPage()">Print</button>
            </div>
            <div class="print">
            <div class="records table-responsive">
                <div class="record-header"></div>
                <div>
                    <table width="100%">
                        <thead>
                            <tr>
                                <th><span class="las la-sort"></span>#</th>
                                <th><span class="las la-sort"></span>First Name</th>
                                <th><span class="las la-sort"></span>Last Name</th>
                                <th><span class="las la-sort"></span>Email</th>
                                <th><span class="las la-sort"></span>Phone Number</th>
                                <th><span class="las la-sort"></span>Gender</th>
                                <th><span class="las la-sort"></span>School Id</th>
                                <th><span class="las la-sort"></span>Address</th>
                                <th><span class="las la-sort"></span>Emergency Number</th>
                                <th><span class="las la-sort"></span>Profile Picture</th>
                                <th><span class="las la-sort"></span>Update</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
include('connect/connect.php');
 $query = "
            SELECT u.*, ri.image_path, o.bhouse_name AS owner_name
            FROM users u
            LEFT JOIN bookings b ON u.email = b.user_email        
            LEFT JOIN room_images ri ON b.image_path = ri.image_path 
            LEFT JOIN owners o ON ri.owner_id = o.id
            ORDER BY u.Id ASC  -- Sorting by Id in ascending order
        ";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($result)) {
    ?>
    <tr>
        <td><?php echo $row['Id']; ?></td>
        <td><?php echo $row['firstName']; ?></td>
        <td><?php echo $row['lastName']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td><?php echo $row['tele']; ?></td>
        <td><?php echo $row['gender']; ?></td>
        <td><?php echo $row['school']; ?></td>
        <td><?php echo $row['address']; ?></td>
        <td><?php echo $row['emergency_contact']; ?></td>
        <td>
            <img src="<?php echo $row['profile_picture']; ?>" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;">
        </td>
        <td>
    <a href="auth/edit.php?id=<?php echo $row['Id']; ?>" class="button update-button">Update</a><br><br>
    <a href="auth/delete.php?id=<?php echo $row['Id']; ?>" onclick="return confirmDelete()" class="button delete-button">Delete</a>
</td>
    </tr>
    <?php
}
?>

                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
    
    <script type="text/javascript">
    function confirmDelete() {
        return confirm("Are you sure you want to delete this user?");
    }
    function printPage() {
            window.print();
        }
    </script>
</body>
</html>
