<?php
// Path: group-chat.php
session_start();
include_once "php/config.php";
if (!isset($_SESSION['unique_id'])) {
    header("location: login.php");
}

$group_id = mysqli_real_escape_string($conn, $_GET['group_id']);
$sql = mysqli_query($conn, "SELECT * FROM groups WHERE group_id = {$group_id}");
if (mysqli_num_rows($sql) > 0) {
    $group = mysqli_fetch_assoc($sql);
} else {
    header("location: users.php");
}
?>
<?php include_once "header.php"; ?>

<!-- Create the `groups` table
CREATE TABLE `groups` (
  `group_id` INT PRIMARY KEY AUTO_INCREMENT,
  `group_name` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- You may also need a table to store group messages (similar to messages table) -->

<body>
  <div class="wrapper">
    <section class="chat-area">
      <header>
        <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <!-- Display group name -->
        <div class="details">
          <span><?php echo $group['group_name']; ?></span>
          <!-- You can add more group info if needed -->
        </div>
      </header>
      <div class="chat-box">
      </div>
      <form action="#" class="typing-area">
        <!-- Include group_id as a hidden field -->
        <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $group_id; ?>" hidden>
        <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
        <button><i class="fab fa-telegram-plane"></i></button>
      </form>
    </section>
  </div>

  <script src="javascript/chat.js"></script>

</body>
</html>
