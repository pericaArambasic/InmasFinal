<?php
if(!isset($_COOKIE['user'])){
    header('Location: login.php');
    exit;
} else {
}

include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
if (isset($_GET['id_inventory'])) {
    $stmt = $pdo->prepare('SELECT * FROM inventory WHERE id_inventory = ?');
    $stmt->execute([$_GET['id_inventory']]);
    $asset = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$asset) {
        exit('Asset doesn\'t exist with that ID!');
    }
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            $stmt = $pdo->prepare('DELETE FROM inventory WHERE id_inventory = ?');
            $stmt->execute([$_GET['id_inventory']]);
            $msg = 'You have deleted the asset!';
        } else {
            header('Location: read.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}
?>

<?=template_header('Delete')?>

<div class="content delete">
    <h2>Delete Contact #<?=$asset['id_inventory']?></h2>
    <?php if ($msg): ?>
        <p><?=$msg?></p>
    <?php else: ?>
        <p>Are you sure you want to delete contact #<?=$asset['id_inventory']?>?</p>
        <div class="yesno">
            <a href="delete.php?id_inventory=<?=$asset['id_inventory']?>&confirm=yes">Yes</a>
            <a href="delete.php?id_inventory=<?=$asset['id_inventory']?>&confirm=no">No</a>
        </div>
    <?php endif; ?>
</div>

<?=template_footer()?>
