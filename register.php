<?php
include 'functions.php';
$pdo = pdo_connect_mysql();

if(isset($_POST['register'])) {
    print_r($_POST);
    $username = $_POST['username'];
    $password = $_POST['password'];
    $encrypt_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute($username);

    if($stmt->rowCount() > 0) {
        echo '<p>This user already exists!</p>';
    }
    if ($stmt->rowCount() == 0) {
        $query = $pdo->prepare("INSERT INTO users(username,password) VALUES (:username,:password)");
        $query->bindParam("username", $username, PDO::PARAM_STR);
        $query->bindParam("password", $encrypt_password, PDO::PARAM_STR);
        $result = $query->execute();
        if ($result) {
            echo '<p class="success">Your registration was successful!</p>';
            header('Location: login.php');
        } else {
            echo '<p class="error">Something went wrong!</p>';
        }
    }
}
?>

<?=template_header('Register')?>

    <form action="" method="post" class="p-3" name="register">
        <h1 class="d-flex justify-content-center">Create new account</h1>
        <div class="d-flex justify-content-center ">
            <input type="text" class="form-control w-25 p-3" name="username" placeholder="Username" required>
        </div>
        <div class="d-flex justify-content-center">
            <input type="password" class="form-control w-25 p-3" name="password" placeholder="Password" required>
        </div>
        <br>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-primary" name="register" value="register">Register</button>
        </div>
    </form>

<?=template_footer()?>