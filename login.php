<?php
session_start([
    "cookie_lifetime" => 300 // 5min
]);



$userName = $_POST['user'] ?? '';
$password = $_POST['password'] ?? '';
$userName = htmlspecialchars(trim($userName));
$password = htmlspecialchars(trim($password));

$fp = fopen("./data/user.text", "r");

if ($userName && $password) {
    $_SESSION['attempt'] = true;
    $found = false;

    while ($data = fgetcsv($fp, 0, ",", "\"", "\\")) {
        if ($data[0] === $userName) {
            $found = true;
            if ($data[1] === sha1($password)) {
                $_SESSION["loggedin"] = true;
                $_SESSION["user"] = $userName;
                header("Location: index.php");
                exit;
            } else {
                $_SESSION["loggedin"] = false;
            }
            break;
        }
    }

    if (!$found) {
        $_SESSION["loggedin"] = false;
    }
    fclose($fp);
}




if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    $_SESSION = [];
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simple Login Form</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
</head>
<body>

<section class="contact-area" style="margin-top: 200px;">
    <div class="container">
        <div class="row">
            <div class="column column-60 column-offset-20">
                <h2 class="text-center">Simple Auth Example</h2>
                <?php 
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                    echo "<p style='color:green;'>Hello Admin , Welcome!</p>";
                } else {
                    echo "<p>Hello Stranger , Login Below</p>";
                }
                ?>

            </div>
        </div>
        <div class="contact-form" style="margin-top: 100px;">
            <div class="row">
                <div class="column column-60 column-offset-20">
                    <?php 
                    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false):
                    ?>
                        <!-- Login Form -->
                        <form method="POST" action="">
                            <fieldset>
                                <label for="user">User Name</label>
                                <input type="text" id="user" name="user" placeholder="Enter your username" required>

                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" placeholder="Enter your password" required>

                                <input class="button-primary" type="submit" value="Login">
                            </fieldset>
                        </form>
                    <?php else: ?>
                        <!-- Logout Form -->
                        <form method="GET" action="">
                            <fieldset>
                                <input type="hidden" name="logout" value="true">
                                <input class="button-primary" type="submit" value="Log Out">
                            </fieldset>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

</body>
</html>
