<?php
session_start();
include_once "php/config.php";
if (!isset($_SESSION['unique_id'])) {
  header("location: login.php");
}
?>
<?php include_once "header.php"; ?>


<style>
  #menu-icon {
    color: black;
  }
</style>

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
            <li><a href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>" class="logout" id="logout">Logout</a></li>
          </ul>
        </div>
      </header>

      <div class="search">
        <span class="text">Select an user to start chat</span>
        <input type="text" placeholder="Enter name to search...">
        <button><i class="fas fa-search"></i></button>
      </div>
      <div class="users-list">

      </div>
    </section>
  </div>

  <script src="javascript/users.js"></script>
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