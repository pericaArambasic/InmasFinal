<?php
include 'functions.php';
$pdo = pdo_connect_mysql();

$tries = getTries();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username ");
    $stmt->bindParam("username",$username,PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo '<p>Wrong username/password.</p>';
        if ($tries == 3) {
            header('Location: login_error.php');
            exit;
        } elseif(empty($tries)) {
            firstTry();
        } else {
            tryIncrement();
        }
    } else {
        if (password_verify($password,$user['password'])) {
            setcookie('user', $user['username'],time()+3600,'/');
            echo '<p>Login succesful!</p>';
            header('Location: index.php');
            exit;
        } else {
            echo '<p>Wrong username/password.</p>';
            if ($tries == 3) {
                header('Location: login_error.php');
            } elseif(empty($tries)) {
                firstTry();
            } else {
                tryIncrement();
            }
                }
            }
        }
?>

<?=template_header('Login')?>

    <form action="" method="post" class="p-5" name="login">
        <div class="d-flex justify-content-center ">
            <input type="text" class="form-control w-25 p-3" name="username" placeholder="Username" required>
        </div>
        <div class="d-flex justify-content-center">
            <input type="password" class="form-control w-25 p-3" name="password" placeholder="Password" required>
        </div>
        <br>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-info" name="login" value="login">Login</button>
        </div>
        <div class="d-flex justify-content-center">
            <a href="register.php" class="link-dark">Create account</a>
        </div>
    </form>

<?=template_footer()?>