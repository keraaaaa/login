<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style2.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <style>
        .dashboard-content {
            margin: 20px;
        }

        .stats {
            display: flex;
            flex-direction: column;
            align-items: flex-start; 
        }

        .card-container {
            display: flex; 
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px 0; 
        }

        .card {
            border: none; 
            border-radius: 15px; 
            transition: transform 0.3s, box-shadow 0.3s; 
            max-width: 250px; 
            height: 250px;
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            text-align: center; 
        }

        .card:hover {
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
        }

        .card-body {
            border-radius: 15px;
            padding: 10px; 
            background-color: #E9edf2;
            flex-grow: 1; 
        }

        .card-body h3 {
            font-size: 2.5rem;
            color: #007bff; 
            margin-bottom: 15px; 
        }

        .card-body img {
            width: 70px; 
            height: auto;
            margin-top: 10px;
        }

        .card-text {
            font-size: 1.5rem;
            color: #6c757d; 
            margin-top: 10px; 
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
                       <a href="stadmin.php" class="active">
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
                </li>
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
                <h1>Dashboard</h1>
                <small>ADMIN Dashboard</small>
            </div>
            <div class="dashboard-content">
                <div class="stats">
                    <div class="card-container">

                        <div class="card text-center">
                            <div class="card-body">
                                <h3 id="idCount" class="display-4"></h3> 
                                <p class="card-text">Total Registered Student</p>
                                <img src="bckgnds/students.png" alt="">
                            </div>
                        </div>

                        <div class="card text-center">
                            <div class="card-body">
                                <h3 id="homeCount" class="display-4"></h3> 
                                <p class="card-text">Total Registered Landlords</p>
                                <img src="bckgnds/income.png" alt="">
                            </div>
                        </div>

                        <div class="card text-center">
                            <div class="card-body">
                                <h3 id="booking_count" class="display-4"></h3> 
                                <p class="card-text">Total Booked Room/s</p>
                                <img src="bckgnds/income.png" alt="">
                            </div>
                        </div>                  
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 id="room_count" class="display-4"></h3> 
                                <p class="card-text">Total Uploaded Room/s</p>
                                <img src="bckgnds/students.png" alt="">
                            </div>
                        </div>
                        <div class="card text-center">
                            <div class="card-body">
                                 <h3 id="total_reservations" class="display-4"></h3> 
                                 <p class="card-text">Total Reservations</p>
                                 <img src="bckgnds/income.png" alt="">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>       
    </div>
    <script src="scripts/script2.js"></script>
    <script>
         fetch('auth/reservation_count.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('total_reservations').innerText = data;
            })
            .catch(error => {
                document.getElementById('total_reservations').innerText = "Error loading data";
            });
        </script>
</body>
</html>
