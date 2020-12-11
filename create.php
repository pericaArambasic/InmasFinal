<?php
if(!isset($_COOKIE['user'])){
    header('Location: login.php');
    exit;
} else {
}
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';

//get values for category select
$stmt2 = $pdo->prepare('select * from categories');
$stmt2->execute();
$data = $stmt2->fetchAll(PDO::FETCH_ASSOC);


// Check if POST data is not empty
if (!empty($_POST)) {

    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    $id = isset($_POST['id_inventory']) && !empty($_POST['id_inventory']) && $_POST['id_inventory'] != 'auto' ? $_POST['id_inventory'] : NULL;
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $name = isset($_POST['inventory_name']) ? $_POST['inventory_name'] : '';
    $category = isset($_POST['id_cat']) ? $_POST['id_cat'] : '';
    $price = isset($_POST['inventory_price']) ? $_POST['inventory_price'] : '';
    // Insert new record into the contacts table
    $stmt = $pdo->prepare('INSERT INTO inventory VALUES (?, ?, ?, ?)');
    $stmt->execute([$id, $name, $category, $price]);
    // Output message
    $msg = 'Created Successfully!';
}
?>

<?=template_header('Create')?>

<div class="content update">
    <h2>Create an Asset</h2>
    <form action="create.php" method="post">
        <label for="id_inventory">ID</label>
        <input type="text" name="id_inventory" value="auto" id="id_inventory" disabled>
        <label for="inventory_name">Name</label>
        <input type="text" name="inventory_name"  id="inventory_name">
        <label for="inventory_price">Price</label>
        <input type="number" name="inventory_price"  id="inventory_price">
        <select name="id_cat" id="id_cat">
            <?php foreach ($data as $row): ?>
                <option value="<?=$row["id_cat"]?>"><?=$row["category_name"]?></option>
            <?php endforeach ?>
        </select>
        <input type="submit" value="Create">
    </form>
    <?php if ($msg): ?>
        <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
