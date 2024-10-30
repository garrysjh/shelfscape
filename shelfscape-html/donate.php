<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Shelfscape</title>
    <meta charset="utf-8" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="styles/reset.css" />
  </head>
  <body>
    <header>
      <nav class="navbar">
        <div class="logo">
            <a href="index.php">
          <img src="assets/icons/shelfscape-logo.png" alt="Shelfscape Logo" />
        </a>
        </div>
        <div class="nav-links">
          <a href="books.php">Books</a>
          <div class="dropdown">
            <a href="#">Categories</a>
            <div class="dropdown-content">
              <a href="books.php?category=Fantasy">Fantasy</a>
              <a href="books.php?category=Fiction">Fiction</a>
              <a href="books.php?category=Romance">Romance</a>
              <a href="books.php?category=Classics">Classics</a>
              <a href="books.php?category=Horror">Horror</a>
            </div>
          </div>
          <a href="events.html">Events</a>
          <a href="aboutus.php">About</a>
          <a href="donate.php">Donate</a>
        </div>
        <div class="search-bar">
          <form action="books.php" method="GET">
            <input type="text" name="query" placeholder="ENTER SERIAL NO OR TITLE" />
            <button type="submit" class="search-button">Search</button>
            <i class="fas fa-search"></i>
          </form>
        </div>
        <div class="account-icon">
          <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
            <div class="dropdown">
              <img src="<?php echo $_SESSION['profilePicture']; ?>" alt="User Icon" class="usericon"/>
              <div class="dropdown-content">
                <a href="profile.php">Profile</a>
                <a href="settings.php">Settings</a>
                <a href="logout.php">Logout</a>
              </div>
            </div>
          <?php else: ?>
            <a href="login.php">
              <img src="assets/icons/user.png" alt="User Icon" class="usericon"/>
            </a>
          <?php endif; ?>
        </div>
      </nav>
    </header>
    <main>
      <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
        <h2>Donate a Book</h2>
        <form action="donate_process.php" method="POST">
          <label for="title">Title:</label>
          <input type="text" id="title" name="title" required><br>

          <label for="author">Author:</label>
          <input type="text" id="author" name="author" required><br>

          <label for="description">Description:</label>
          <textarea id="description" name="description" required></textarea><br>

          <label for="language">Language:</label>
          <input type="text" id="language" name="language" required><br>

          <label for="isbn">ISBN:</label>
          <input type="text" id="isbn" name="isbn" required><br>

          <label for="genres">Genres:</label>
          <input type="text" id="genres" name="genres" required><br>

          <button type="submit">Donate</button>
        </form>
      <?php else: ?>
        <p>Please <a href="login.php">log in</a> to donate a book.</p>
      <?php endif; ?>
    </main>
  </body>
  <script></script>
</html>
