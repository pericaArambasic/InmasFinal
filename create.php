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

if (!empty($_POST)) {
    $id = isset($_POST['id_inventory']) && !empty($_POST['id_inventory']) && $_POST['id_inventory'] != 'auto' ? $_POST['id_inventory'] : NULL;
    $name = isset($_POST['inventory_name']) ? $_POST['inventory_name'] : '';
    $category = isset($_POST['id_cat']) ? $_POST['id_cat'] : '';
    $price = isset($_POST['inventory_price']) ? $_POST['inventory_price'] : '';
    $stmt = $pdo->prepare('INSERT INTO inventory VALUES (?, ?, ?, ?)');
    $timestamp = date("F d, Y h:i:s A", time());
    $message = $timestamp . " New entry created by user:" . $_COOKIE['user'] . "->inventory" . PHP_EOL;
    file_put_contents('logs/table.log', $message, FILE_APPEND);
    $stmt->execute([$id, $name, $category, $price]);
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
