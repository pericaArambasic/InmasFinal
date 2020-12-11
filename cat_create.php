<?php
if(!isset($_COOKIE['user'])){
    header('Location: login.php');
    exit;
} else {
}
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    $id = isset($_POST['id_cat']) && !empty($_POST['id_cat']) && $_POST['id_cat'] != 'auto' ? $_POST['id_cat'] : NULL;
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $name = isset($_POST['category_name']) ? $_POST['category_name'] : '';
    // Insert new record into the contacts table
    $stmt = $pdo->prepare('INSERT INTO categories VALUES (?, ?)');
    $stmt->execute([$id, $name]);
    // Output message
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
