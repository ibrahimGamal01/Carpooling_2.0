<?php
// Path: users.php
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

  #users_page {
    background-color: darkcyan;
    color: white;
  }

  /* Add these styles at the bottom of your existing styles */
  .create-group-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
  }

  .create-group-button button {
    background-color: darkcyan;
    color: white;
    border: none;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    font-size: 20px;
    cursor: pointer;
  }

  .create-group-button button:hover {
    background-color: teal;
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
            <li><a href="users.php" class="logout" id="users_page">Chat</a></li>
            <li><a href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>" class="logout"
                id="logout">Logout</a></li>
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

  <div class="create-group-button">
    <button id="create-group">Create Group</button>
  </div>


  <script src="javascript/users.js"></script>
  <script>
    let menu = document.querySelector('#menu-icon');
    let navList = document.querySelector('.nav_items');

    menu.onclick = () => {
      menu.classList.toggle('bx-x');
      navList.classList.toggle('open');
    }

    const createGroupButton = document.getElementById('create-group');

    createGroupButton.addEventListener('click', () => {
      const groupName = prompt('Enter the group name:');
      if (groupName) {
        fetch('php/create-group.php', {
          method: 'POST',
          body: JSON.stringify({ groupName }),
          headers: {
            'Content-Type': 'application/json',
          },
        })
          .then(response => response.json())
          .then(data => {
            // Handle the response from the server (e.g., display success message)
            if (data.success) {
              alert(data.success); // Display success message
              // You can also update the group list or take other actions as needed
            } else {
              alert(data.error); // Display error message
            }
          })
          .catch(error => {
            // Handle any errors
            console.error('Error creating group:', error);
          });
      }
    });


  </script>

</body>

</html>