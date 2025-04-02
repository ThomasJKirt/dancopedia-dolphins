<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="../public/css/Password.css">
</head>

<body>


  <div class="form-container">

    <form action="send_password.php" method="post">
      <h3>Forgot Password</h3>
      <?php
      if (isset($error)) {
        foreach ($error as $error) {
          echo '<span class="error-msg">' . $error . '</span>';
        };
      };
      ?>
      <input type="email" name="email" required placeholder="enter your email">
      <input type="submit" name="submit" value="Reset" class="form-btn">
      <p>don't have an account? <a href="register_form.php">register now</a></p>
    </form>

    <!-- Back Button -->
    <button type="button" onclick="window.location.href='index.html'" class="back-btn">Back</button>

  </div>

</body>

</html>