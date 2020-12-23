<?php
if(!isset($_COOKIE['user'])){
    header('Location: login.php');
    exit;
} else {
}
include 'functions.php';

$search = '';
$pdo = pdo_connect_mysql();
$search = $_POST['inventory_name'];
if (!isset($_POST['sort'])) {
    $stmt = $pdo->prepare('SELECT i . id_inventory, inventory_name, inventory_price, c . category_name  
                                FROM inventory i, categories c  
                                WHERE i . inventory_name LIKE ? AND  i.id_cat = c.id_cat');
} elseif ($_POST['sort'] == 'asc') {
    $stmt = $pdo->prepare('SELECT i . id_inventory, inventory_name, inventory_price, c . category_name  
                                FROM inventory i, categories c  
                                WHERE i . inventory_name LIKE ? AND  i.id_cat = c.id_cat ORDER BY inventory_price ASC');
} elseif ($_POST['sort'] == 'desc') {
    $stmt = $pdo->prepare('SELECT i . id_inventory, inventory_name, inventory_price, c . category_name  
                                FROM inventory i, categories c  
                                WHERE i . inventory_name LIKE ? AND  i.id_cat = c.id_cat ORDER BY inventory_price DESC ');
}
$stmt->execute(array("%$search%"));
$search_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?=template_header('Home')?>

    <div class="content">
        <div class="content update">
            <h2>SEARCH</h2>
            <form action="index.php" method="post">
                <input type="text" id="inventory_name" name="inventory_name">
                <input type="submit" value="Search">
            </form>
    </div>
    <form action="index.php" method="post">
        <h4>Price:</h4>
        <input type="submit" class="btn btn-info" style="color: white" value="sort">
        <select name="sort" id="sort">
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
        </select>
        <br>
    </form>
    <?=print_r($_POST) ?>

    <div class="content read">
    <h2>Assets</h2>
    <table>
        <thead>
        <tr>
            <td>#</td>
            <td>Name</td>
            <td>Category</td>
            <td>Price</td>
            <td></td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($search_result as $result): ?>
            <tr>
                <td><?=$result['id_inventory']?></td>
                <td><?=$result['inventory_name']?></td>
                <td><?=$result['category_name']?></td>
                <td><?=$result['inventory_price']?></td>
                <td></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>


<?=template_footer()?>