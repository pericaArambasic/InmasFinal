<?php
function pdo_connect_mysql() {
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = 'password';
    $DATABASE_NAME = 'inmas';
    try {
        return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);

    } catch (PDOException $exception) {
        exit('Failed to connect to database!');
    }
}
function template_header($title) {
    //print_r($_SESSION);
    echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>$title</title>
		<link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Padauk:wght@700&display=swap" rel="stylesheet">
		<link href="style.css" rel="stylesheet" type="text/css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
    <nav class="navtop">
    	<div>
    		<h1 class="brand">Inmas</h1>
            <a href="index.php"><i class="fas fa-home"></i>Home</a>
    		<a href="read.php"><i class="fas fa-address-book"></i>Inventory</a>
    		<a href="cat_read.php"><i class="fas fa-address-book"></i>Category</a>
    		<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
    	</div>
    </nav>
EOT;
}
function template_footer() {
    echo <<<EOT
    </body>
</html>
EOT;
}

function getIp() {
    $ip = $_SERVER['REMOTE_ADDR'];
    return $ip;
}

function getTries() {
    $ip = getIp();
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare("SELECT * FROM logtries WHERE ip_address = :ip_address");
    $stmt->bindParam("ip_address", $ip, PDO::PARAM_STR);
    $stmt->execute();
    $data =$stmt->fetch(PDO::FETCH_ASSOC);
    $result = $data['attempts'];
    return $result;
}

function firstTry() {
    $ip = getIp();
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare("INSERT INTO logtries(ip_address, attempts) VALUES(:ip_address,1)");
    $stmt->bindParam("ip_address", $ip, PDO::PARAM_STR);
    $stmt->execute();
}

function tryIncrement() {
    $ip = getIp();
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare("UPDATE logtries SET attempts = attempts + 1 WHERE ip_address = :ip_address");
    $stmt->bindParam("ip_address", $ip, PDO::PARAM_STR);
    $stmt->execute();
}

function resetTries() {
    $ip = getIp();
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare("DELETE FROM logtries WHERE ip_address = :ip_address");
    $stmt->bindParam("ip_address", $ip, PDO::PARAM_STR);
    $stmt->execute();
    header('refresh:30;url=login.php');
}
?>