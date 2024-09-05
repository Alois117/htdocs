<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <div class="prof">
        <div class="container">
            <h2>Reset Password</h2>
        
            <?php
            if (count($errors) > 0):
            ?>
            <div class="alert alert-danger">
                <?php foreach($errors as $error): ?>
                <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <form action="reset_password.php" method="post">
                <div class="form-group">
                    <label for="password">Enter password:</label>
                    <input type="password" id="password" name="password" >
                </div>
                <div class="form-group">
                    <label for="confpassword">Confirm Password:</label>
                    <input type="password" id="confpassword" name="confpassword" >
                </div>
                <div class="form-group">
                    <button type="submit" name="reset-btn" class="reset-btn">Reset Password</button>
                </div>
            
            </form>
        </div>
    </div>
</body>
</html>