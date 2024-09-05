<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in</title>
    <link rel="stylesheet" type="text/css" href="sign up.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="main">
    
</div>

<div class="wrapper">
    <form action="signin.php" method="POST">
        <h1>Login</h1>
        <div class="input-box">
            <input type="text" name="Username" id="Username" placeholder="Username" required>
        </div>
        <?php if (isset($_SESSION['errors']['username'])): ?>
            <div class="error-message"><?php echo $_SESSION['errors']['username']; ?></div>
        <?php endif; ?>
        
        <div class="input-box">
            <input type="password" name="Password" id="Password" placeholder="Password" required>
        </div>
        <?php if (isset($_SESSION['errors']['login_fail'])): ?>
            <div class="error-message"><?php echo $_SESSION['errors']['login_fail']; ?></div>
        <?php endif; ?>
        
        <div class="remember-forgot">
            <label><input type="checkbox" name="RememberMe">Remember Me</label>
            <a href="#">Forgot Password</a>
        </div>
        <button type="submit" class="btn" name="signinbtn">Login</button>
        <div class="register-link">
            <p>Don't have an account? <a href="sign up.html">Register</a></p>                
        </div>
    </form>     
</div>
</body>
</html>