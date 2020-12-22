<?php
if(!isset($_COOKIE['user'])){
    header('Location: login.php');
    exit;
} else {
}
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';

$stmt2 = $pdo->prepare('select * from categories');
$stmt2->execute();
$data = $stmt2->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['id_inventory'])) {
    if (!empty($_POST)) {
        $id = isset($_POST['id_inventory']) ? $_POST['id_inventory'] : NULL;
        $name = isset($_POST['inventory_name']) ? $_POST['inventory_name'] : '';
        $category = isset($_POST['id_cat']) ? $_POST['id_cat'] : '';
        $price = isset($_POST['inventory_price']) ? $_POST['inventory_price'] : '';
        $stmt = $pdo->prepare('UPDATE inventory SET  inventory_name = ?, id_cat = ?, inventory_price = ?  WHERE id_inventory = ?');
        $timestamp = date("F d, Y h:i:s A", time());
        $message = $timestamp . " Entry updated by user:" . $_COOKIE['user'] ."->inventory" . PHP_EOL;
        file_put_contents('logs/table.log', $message, FILE_APPEND);
        $stmt->execute([$name, $category, $price, $_GET['id_inventory']]);
        $msg = 'Updated Successfully!';

    }
    $stmt = $pdo->prepare('SELECT * FROM inventory WHERE id_inventory = ?');
    $stmt->execute([$_GET['id_inventory']]);
    $asset = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$asset) {
        exit('Asset doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}
?>

<?=template_header('Read')?>

<div class="content update">
    <h2>Update Asset</h2>
    <form action="update.php?id_inventory=<?=$asset['id_inventory']?>" method="post">
        <label for="inventory_name">Name</label>
        <input type="text" name="inventory_name" value="<?=$asset['inventory_name']?>" id="inventory_name">
        <label for="inventory_price">Price</label>
        <input type="text" name="inventory_price"  value="<?=$asset['inventory_price']?>" id="inventory_price">
        <label for="id_cat">Category</label>
        <select name="id_cat" id="id_cat">
            <?php foreach ($data as $row): ?>
                <option value="<?=$row["id_cat"]?>"><?=$row["category_name"]?></option>
            <?php endforeach ?>
        </select><br>
            <input type="submit" value="Update">
    </form>
    <?php if ($msg): ?>
        <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
