<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style2.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <style>
.tables-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        table {
            width: 100%;
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
    color: #444;
    font-size: 1rem;
}
table tbody td {
    padding: 1rem 0rem;
    color: #444;
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

.buttons {
    display: inline-block;
    padding: 10px 20px;
    font-size: 14px;
    text-align: center;
    text-decoration: none;
    border-radius: 5px;
    cursor: pointer;
    border: none;
    margin: 5px;
    transition: background-color 0.3s ease;
}

.buttons a {
    color: black;
    text-decoration: none; 
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
.btn-print {
    background-color:rgb(242, 255, 0);
            margin-left: 10px;
        }

        .btn-print:hover {
            background: rgb(245, 250, 153);
        }
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
        @media print {
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .sidebar {
        display: none;
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
        margin: 0 auto; 
    }

    th, td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    .download-button {
        display: none;
    }
    .buttons {
        display: none;
    }
    .btn{
        display:none;
    }
    td:first-child, th:first-child{
        display:none;
    }
    td:last-child, th:last-child {
        display: none;
    }
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
                       <a href="settings.php">
                            <span class="las la-user-graduate"></span>
                            <small>Student Log</small>
                        </a>
                    </li>
                    <li>
                       <a href="home_owner.php" class="active">
                            <span class=" las la-user-tie"></span>
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
                <h1>Home Owner Logged in Dashboard</h1>
                <button class="btn btn-print" onclick="printPage()">Print</button>
            </div>
            <div class="print">
                <div class="records table-responsive">
                    <div class="record-header"></div>
                    </div>
                    <div>
                        <table width="100%">
                            <thead>
                                <tr>
                                    <th><span class="las la-sort"></span>#</th>
                                    <th ><span class="las la-sort"></span>First Name</th>
                                    <th ><span class="las la-sort"></span>Last Name</th>
                                    <th ><span class="las la-sort"></span>BHouse Name</th>
                                    <th ><span class="las la-sort"></span>Email</th>
                                    <th ><span class="las la-sort"></span>Phone Number</th>
                                    <th ><span class="las la-sort"></span>Address</th>
                                    <th ><span class="las la-sort"></span></span>Update</th>
                                </tr>
                            </thead>
                            <tbody>
           <?php
             include('connect/connect.php');
              $query=mysqli_query($conn,"select * from `owners`");
             while($row=mysqli_fetch_array($query)){
            ?>
          <tr>
          <td><?php echo $row['id']; ?></td>
               <td><?php echo $row['first_name']; ?></td>
                <td><?php echo $row['last_name']; ?></td>
                <td><?php echo $row['bhouse_name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['contact']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td>
    <button class="buttons update-button">
        <a href="auth/editOwner.php?id=<?php echo $row['id']; ?>">Update</a>
    </button>
    <button class="buttons delete-button">
        <a href="auth/deleteOwner.php?id=<?php echo $row['id']; ?>" onclick="return confirmDelete()">Delete</a>
    </button>
</td>

    </tr>
           <?php
         }
     ?>    
     </main>
    </tbody>
                        </table>
                    </div>
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