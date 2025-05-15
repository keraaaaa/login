<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style6.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:rgb(134, 178, 244);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            text-align: left;
            padding: 20px;
            flex: 1;
        }
        .section-title {
            font-size: 36px;
            margin-bottom: 20px;
            color: #333;
        }
        .images-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 40px;
        }
        .image-item {
            width: 270px;
            height: auto;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 3px solid #ddd;
            background-color: #fff;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .image-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 30%;
        }
        .image-item p {
            font-size: 16px;
            color: #555;
            margin-top: 10px;
        }
        .image-item .description {
            font-size: 14px;
            color: #666;
            margin-top: 10px;
        }
        .image-item .contact-info {
            font-size: 14px;
            color: #777;
            margin-top: 5px;
        }
        footer {
            background-color: #fff;
            padding: 20px;
            text-align: center;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            margin-top: auto;
        }
        .rating {
            display: inline-block;
            position: relative;
        }
        .stars {
            font-size: 30px;
            color: #ffcc00;
            margin-bottom: 20px;
        }
        .rate-button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .rate-button:hover {
            background-color: #0056b3;
        }

        .rating:not(:checked) > input {
            position: absolute;
            appearance: none;
        }

        .rating:not(:checked) > label {
            float: right;
            cursor: pointer;
            font-size: 40px;
            color: #666;
        }

        .rating:not(:checked) > label:before {
            content: '★';
        }

        .rating > input:checked + label:hover,
        .rating > input:checked + label:hover ~ label,
        .rating > input:checked ~ label:hover,
        .rating > input:checked ~ label:hover ~ label,
        .rating > label:hover ~ input:checked ~ label {
            color: #e58e09;
        }

        .rating:not(:checked) > label:hover,
        .rating:not(:checked) > label:hover ~ label {
            color: #ff9e0b;
        }

        .rating > input:checked ~ label {
            color: #ffa723;
        }
        ul li a:hover{
            background-color:rgb(242, 255, 0);
        }
    </style>
</head>
<body>

<nav>
      <div class="nav__logo">CTU-DB | BOARDING HOUSE LOCATOR & MANAGEMENT SYSTEM</div>
      <ul class="nav__links">
            <li><a href="index.php">Home</a></li>
        </ul>
</nav>

<div class="container">
    <h1 class="section-title">About Us</h1>
    
    <div class="images-container">

        <div class="image-item">
            <img src="bckgnds/rex.jpeg" alt="Team Member 1">
            <p><b><u>Rex</u></b></p>
            <div class="contact-info">
            <p><b>Name:</b> Rex Tabanao</p>
            <p><b>Address:</b> Logon, Daanbantayan, Cebu</p>
            <p><b>Contact number:</b> 09945385112</p>
            <p><b>Email:</b> rextabanao123@gmail.com</p>
            </div>
        </div>
 
        <div class="image-item">
            <img src="bckgnds/NICE.jpg" alt="Team Member 2">
            <p><b><u>Eunice</u></b></p>
            <div class="contact-info">
            <p><b>Name:</b> Eunice L. Silvano</p>
            <p><b>Address</b>: Maya, Daanbantayan, Cebu</p>
            <p><b>Contact number:</b> 09208153770</p>
            <p><b>Email:</b> Ambisyosang.froggie19@gmail.com</p>
            </div>
        </div>

        <div class="image-item">
            <img src="bckgnds/m.georget.png" alt="Team Member 3">
            <p><b><u>Merry Georget</u></b></p>
            <div class="contact-info">
            <p><b>Name:</b> Merry Georget Ca-ayon</p>
            <p><b>Address:</b> Poblacion, Daanbantayan, Cebu</p>
            <p><b>Contact number:</b> 09682418221 </p>
            <p><b>Email:</b> caayonmerrygeorjet72@gmail.com</p>
            </div>
        </div>

        <div class="image-item">
            <img src="bckgnds/m.ison.png" alt="Team Member 4">
            <p><b><u>Mark Ison</u></b></p>
            <div class="contact-info">
            <p><b>Name:</b> Mark Ison Ramos</p>
            <p><b>Address:</b> Malbago, Daanbantayan, Cebu</p>
            <p><b>Contact number:</b> 09103708006</p>
            <p><b>Email:</b> markisonramos0@gmail.com<p>
            </div>
        </div>

        <div class="image-item">
            <img src="bckgnds/m.ivan.png" alt="Team Member 5">
            <p><b><u>Mark Ivan</u></b></p>
            <div class="contact-info">
            <p><b>Name:</b> Mark Ivan Pascobello</p>
            <p><b>Address:</b> Logon, Daanbantayan, Cebu</p>
            <p><b>Contact:</b> 09933953658</p>
            <p><b>Email:</b> pascobellomarkivan12@gmail.com </p>
            </div>
        </div>

        <div class="image-item">
            <img src="bckgnds/brian.png" alt="Team Member 6">
            <p><b><u>Brian Ace</u></b></p>
            <div class="contact-info">
            <p><b>Name:</b> Brian Ace Duba</p>
            <p><b>Address:</b> Panugnawan, Medellin, Cebu</p>
            <p><b>Contact:</b> 09287873457</p>
            <p><b>Email:</b> dubabrianace@gmail.com</p>
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="rating">
        <input value="5" name="rate" id="star5" type="radio">
        <label title="5 stars" for="star5"></label>
        <input value="4" name="rate" id="star4" type="radio">
        <label title="4 stars" for="star4"></label>
        <input value="3" name="rate" id="star3" type="radio">
        <label title="3 stars" for="star3"></label>
        <input value="2" name="rate" id="star2" type="radio">
        <label title="2 stars" for="star2"></label>
        <input value="1" name="rate" id="star1" type="radio">
        <label title="1 star" for="star1"></label>
    </div>
    <br>
    <a href="rate.php">
    <button class="rate-button">Rate Us</button></a>
</footer>

</body>
</html>
