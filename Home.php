<?php
include_once "auth_check.php"; // Include the authentication check
include_once "php/config.php"; // Include other necessary files
?>

<?php include_once "header.php"; ?>


<head>
    <style>
        body {
            background-image: url(src/unfccc_background.jpg);
        }
        .btn-container {
            display: flex;
            /* justify-content: center;
            align-items: center; */
        }

        .btn {
            display: inline-block;
            width: 250px;
            height: 250px;
            margin: 10px;
            background-color: #3e9bc699;
            color: rgb(0, 0, 0);
            font-weight: bold;
            font-size: 18px;
            border: none;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }

        .btn:hover {
            background: -webkit-gradient(linear,
                    left top,
                    right top,
                    from(#06877a83),
                    to(#201eae6c));
            color: #ffffff;
        }

        .inpage {
            display: inline-block;
        }

        @media (max-width: 950px) {
            .btn-container {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 250px;
                height: 150px;
            }
        }

        :root {
            --card-height: 37vh;
            --card-width: calc(var(--card-height) / 1.5);
            --forest-color: #228B22;
            --sky-color: #87CEEB;
        }

        .btn-container {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            margin-top: 2rem;
        }

        .btn {
            background: linear-gradient(var(--rotate),
                    var(--forest-color),
                    #3c67e3 43%,
                    var(--sky-color));
            width: var(--card-width);
            height: var(--card-height);
            padding: 3px;
            position: relative;
            border-radius: 6px;
            justify-content: center;
            align-items: center;
            text-align: center;
            display: flex;
            font-size: 1.5em;
            color: rgb(255, 255, 255);
            cursor: pointer;
            font-family: cursive;
            transition: transform 5s linear;
            /* Slower movement */
        }

        .btn:hover {
            color: rgb(255, 255, 255);
            transform: rotate(10deg);
            /* Adjust rotation for hovering */
            transition: color 1s, transform 2s linear;
            /* Add transform transition */
        }

        .btn:hover:before,
        .btn:hover:after {
            animation: none;
            opacity: 0;
        }

        .btn::before {
            content: "";
            width: 104%;
            height: 102%;
            border-radius: 8px;
            background-image: linear-gradient(var(--rotate),
                    var(--forest-color),
                    #3c67e3 43%,
                    var(--sky-color));
            position: absolute;
            z-index: -1;
            top: -1%;
            left: -2%;
            animation: spin 5s linear infinite;
        }

        .btn::after {
            position: absolute;
            content: "";
            top: calc(var(--card-height) / 6);
            left: 0;
            right: 0;
            z-index: -1;
            height: 100%;
            width: 100%;
            margin: 0 auto;
            transform: scale(0.8);
            filter: blur(calc(var(--card-height) / 6));
            background-image: linear-gradient(var(--rotate),
                    var(--forest-color),
                    #3c67e3 43%,
                    var(--sky-color));
            opacity: 1;
            transition: opacity 0.5s;
            animation: spin 5s linear infinite;
            /* Slower rotation */
        }

        @keyframes spin {
            0% {
                --rotate: 0deg;
            }

            100% {
                --rotate: 360deg;
            }
        }

        .car-icon {
            width: 100px;
            height: 100px;
            z-index: 1;
        }

        .details {
            margin-left: 15px;
            text-decoration: solid;
            color: #000000;
        }
        #logout{
            background-color: #201eae6c;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <section class="users">

            <header>
                <div class="content">
                    <?php
                    $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
                    if (mysqli_num_rows($sql) > 0) {
                        $row = mysqli_fetch_assoc($sql);
                    }
                    ?>
                    <img src="php/images/<?php echo $row['img']; ?>" alt="">
                    <div class="details">
                        <span>
                            <?php echo $row['fname'] . " " . $row['lname'] ?>
                        </span>
                        <p>
                            <?php echo $row['status']; ?>
                        </p>
                    </div>
                </div>
                <div class="navigate">
                    <div class="bx bx-menu" id="menu-icon"></div>
                    <ul class="nav_items">
                        <li><a href="Home.php" class="logout">Home</a></li>
                        <li><a href="passenger.php" class="logout">Passenger</a></li>
                        <li><a href="driver.php" class="logout">Driver</a></li>
                        <li><a href="users.php" class="logout">Chat</a></li>
                        <li><a href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>"
                                class="logout" id="logout">Logout</a></li>
                    </ul>
                </div>
            </header>
                <div class="btn-container">
                    <a href="passenger.php" class="btn inpage">I'm a Passenger <img src="src/car-seat-with-seatbelt.svg"
                            alt="Car Icon" class="car-icon"></a>

                    <a href="driver.php" class="btn inpage">I'm a Driver <img src="src/nice-car.svg" alt="Car Icon"
                            class="car-icon"></a>
                </div>
        </section>
    </div>
    <script>
        let menu = document.querySelector('#menu-icon');
        let navList = document.querySelector('.nav_items');

        menu.onclick = () => {
            menu.classList.toggle('bx-x');
            navList.classList.toggle('open');
        }
    </script>
</body>

</html>