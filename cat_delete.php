<?php
if(!isset($_COOKIE['user'])){
    header('Location: login.php');
    exit;
} else {
}


include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the contact ID exists
if (isset($_GET['id_cat'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id_cat = ?');
    $stmt->execute([$_GET['id_cat']]);
    $cat = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$cat) {
        exit('Asset doesn\'t exist with that ID!');
    }
    // Make sure the user confirms before deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM categories WHERE id_cat = ?');
            $stmt->execute([$_GET['id_cat']]);
            $msg = 'You have deleted the category!';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: /categories/cat_read.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}
?>

<?=template_header('Delete')?>

<div class="content delete">
    <h2>Delete category #<?=$cat['id_cat']?></h2>
    <?php if ($msg): ?>
        <p><?=$msg?></p>
    <?php else: ?>
        <p>Are you sure you want to delete category #<?=$cat['id_cat']?>?</p>
        <div class="yesno">
            <a href="cat_delete.php?id_cat=<?=$cat['id_cat']?>&confirm=yes">Yes</a>
            <a href="cat_delete.php?id_cat=<?=$cat['id_cat']?>&confirm=no">No</a>
        </div>
    <?php endif; ?>
</div>

<?=template_footer()?>
