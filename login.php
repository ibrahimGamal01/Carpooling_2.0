<?php
session_start();
if (isset($_SESSION['unique_id'])) {
    header("location: Home.html");
}
?>

<?php include_once "header.php"; ?>

<head>
    <style>
        body {
            background-image: url("src/unfccc_background.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            font-family: 'Work Sans', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .hero {
            display: flex;
            justify-content: center;
            align-items: center;
            /* width: 100%; */
            width: fit-content;
            background-color: #06a600;
            height: 100%;
        }

        .sidebar-container {
            background-color: rgba(113, 112, 112, 0.21);
            /* padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: row;
            align-items: center; */
        }

        .sidebar {
            padding: 20px;
        }

        .welcome-box {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-right: 20px;
        }

        .registration-form {
            display: flex;
            flex-direction: column;
        }

        .btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #45a049;
            color: black;
            border-color: black;
        }

        .sidebar-container {
            display: flex;
            justify-content: space-between;
            background-color: #50199c;
        }

        .welcome-box {
            width: 50%;
        }

        .sidebar {
            width: 50%;
        }

        .registration-title {
            font-size: 2rem;
            color: #4CAF50;
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }

        .registration-title:before {
            content: "";
            position: absolute;
            width: 100%;
            height: 2px;
            background-color: #4CAF50;
            bottom: -10px;
            left: 0;
        }

        /* Login & Signup Form CSS Start */
        .form {
            padding: 25px 30px;
        }

        .form header {
            font-size: 25px;
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: 1px solid #e6e6e6;
        }

        .form form {
            margin: 20px 0;
        }

        .form form .error-text {
            color: #721c24;
            padding: 8px 10px;
            text-align: center;
            border-radius: 5px;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            margin-bottom: 10px;
            display: none;
        }

        .form form .name-details {
            display: flex;
        }

        .form .name-details .field:first-child {
            margin-right: 10px;
        }

        .form .name-details .field:last-child {
            margin-left: 10px;
        }

        .form form .field {
            display: flex;
            margin-bottom: 10px;
            flex-direction: column;
            position: relative;
        }

        .form form .field label {
            margin-bottom: 2px;
        }

        .form form .input input {
            height: 40px;
            width: 100%;
            font-size: 16px;
            padding: 0 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form form .field input {
            outline: none;
        }

        .form form .image input {
            font-size: 17px;
        }

        .form form .button input {
            height: 45px;
            border: none;
            color: #fff;
            font-size: 17px;
            background: #333;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 13px;
        }

        .form form .field i {
            position: absolute;
            right: 15px;
            top: 70%;
            color: #ccc;
            cursor: pointer;
            transform: translateY(-50%);
        }

        .form form .field i.active::before {
            color: #333;
            content: "\f070";
        }

        .form .link {
            text-align: center;
            margin: 10px 0;
            font-size: 17px;
        }

        .form .link a {
            color: #000000;
            font-weight: bold;
        }

        .form .link a:hover {
            text-decoration: underline;
        }
    </style>

</head>

<body>
    <!-- <section class="hero">
        <div class="sidebar-container">
            <div class="welcome-box">
                <h2>Welcome to Carpooling UNFCCC</h2>
                <p>Dear Carpooling Enthusiast,</p>
                <p>Join us in the fight against climate change through ride-sharing and reducing carbon emissions.</p>
                <p>At Carpooling UNFCCC, every shared ride makes a difference, contributing to a cleaner and greener
                    planet.</p>
                <p><strong>Register now and be part of the solution!</strong></p>
                <p>The Carpooling UNFCCC Team</p>
            </div>
            <div class="sidebar">
                <h1 class="registration-title">Join the Carpooling Community</h1>
                <form action="login.html" class="registration-form">
                    <label for="Name">Name:</label>
                    <input type="text" id="Name" name="Name" placeholder="Name" required>

                    <label for="Email">Email:</label>
                    <input type="email" id="Email" name="Email" placeholder="Email" required>

                    <label for="Password">Password:</label>
                    <input type="password" id="Password" name="Password" placeholder="Password" required>

                    <button type="submit" class="btn">Register</button>
                </form>
            </div>
        </div>
    </section> -->

    <section class="hero">
        <div class="sidebar-container">
            <div class="wrapper">
                <section class="form login">
                    <header>Realtime Chat App</header>
                    <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="error-text"></div>
                        <div class="field input">
                            <label>Email Address</label>
                            <input type="text" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="field input">
                            <label>Password</label>
                            <input type="password" name="password" placeholder="Enter your password" required>
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="field button">
                            <input type="submit" name="submit" value="Continue to Chat">
                        </div>
                    </form>
                    <div class="link">Not yet signed up? <a href="index.php">Signup now</a></div>
                </section>
            </div>
        </div>
    </section>

    <script src="javascript/pass-show-hide.js"></script>
    <script src="javascript/login.js"></script>

</body>

</html>