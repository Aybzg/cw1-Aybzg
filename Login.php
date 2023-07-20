<?php
$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mysqli = require __DIR__ . "/db.php";
    
    $email = $mysqli->real_escape_string($_POST["email"]);
    $sql = sprintf("SELECT * FROM user WHERE email = '%s'", $email);
    
    $result = $mysqli->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($_POST["password"], $user["password"])) {
            session_start();
            session_regenerate_id(); //Prevents session fixation attack
            $_SESSION["user_id"] = $user["id"];
            header("Location: index.php");
            exit;
        }
    }
    
    $is_invalid = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>
  <div class="login-box">
    <h2>Login</h2>
    <form method="post" action="">
      <div class="user-box">
        <input type="text" name="email" required="">
        <label>Email</label>
      </div>
      <div class="user-box">
        <input type="password" name="password" required="">
        <label>Password</label>
      </div>
      <button type="submit">Submit</button>
    </form>
    <?php if ($is_invalid): ?>
      <p>Invalid email or password</p>
    <?php endif; ?>
  </div>
</body>
</html>
