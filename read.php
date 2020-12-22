<?php
if(!isset($_COOKIE['user'])){
    header('Location: login.php');
    exit;
} else {
}

include 'functions.php';

$pdo = pdo_connect_mysql();

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

$records_per_page = 5;


$stmt = $pdo->prepare('SELECT i . id_inventory, inventory_name, inventory_price, c . category_name  
                                FROM inventory i INNER JOIN categories c
                                WHERE i.id_cat = c.id_cat ORDER BY id_inventory LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();

$inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);


$num_inventory = $pdo->query('SELECT COUNT(*) FROM inventory')->fetchColumn();
?>

<?=template_header('Read')?>

    <div class="content read">
        <h2>Assets</h2>
        <a href="create.php" class="create-contact">Create an Asset</a>
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
            <?php foreach ($inventory as $asset): ?>
                <tr>
                    <td><?=$asset['id_inventory']?></td>
                    <td><?=$asset['inventory_name']?></td>
                    <td><?=$asset['category_name']?></td>
                    <td><?=$asset['inventory_price']?></td>
                    <td class="actions">
                        <a href="update.php?id_inventory=<?=$asset['id_inventory']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                        <a href="copy.php?id_inventory=<?=$asset['id_inventory']?>" class="edit" ><i class="fas fa-copy"></i></a>
                        <a href="delete.php?id_inventory=<?=$asset['id_inventory']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="read.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
            <?php endif; ?>
            <?php if ($page*$records_per_page < $num_inventory): ?>
                <a href="read.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
            <?php endif; ?>
        </div>
    </div>

<?=template_footer()?>