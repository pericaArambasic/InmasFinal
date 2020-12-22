<?php
if(!isset($_COOKIE['user'])){
    header('Location: login.php');
    exit;
} else {
}
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
if (!empty($_POST)) {
    $id = isset($_POST['id_cat']) && !empty($_POST['id_cat']) && $_POST['id_cat'] != 'auto' ? $_POST['id_cat'] : NULL;
    $name = isset($_POST['category_name']) ? $_POST['category_name'] : '';
    $stmt = $pdo->prepare('INSERT INTO categories VALUES (?, ?)');
    $timestamp = date("F d, Y h:i:s A", time());
    $message = $timestamp . " New entry created by user:" . $_COOKIE['user'] . "->categories" . PHP_EOL;
    file_put_contents('logs/table.log', $message, FILE_APPEND);
    $stmt->execute([$id, $name]);
    $msg = 'Created Successfully!';
}
?>

<?=template_header('Create')?>

<div class="content update">
    <h2>Create a category</h2>
    <form action="cat_create.php" method="post">
        <label for="category_name">Name</label>
        <input type="text" name="category_name"  id="category_name">
        <input type="submit" value="Create">
    </form>
    <?php if ($msg): ?>
        <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
