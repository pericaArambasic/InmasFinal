<?php
if(!isset($_COOKIE['user'])){
    header('Location: login.php');
    exit;
} else {
}

include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
if (isset($_GET['id_cat'])) {
    if (!empty($_POST)) {
        $id = isset($_POST['id_cat']) ? $_POST['id_cat'] : NULL;
        $name = isset($_POST['category_name']) ? $_POST['category_name'] : '';
        $stmt = $pdo->prepare('UPDATE categories SET  category_name = ?  WHERE id_cat = ?');
        $timestamp = date("F d, Y h:i:s A", time());
        $message = $timestamp . " Entry updated by user:" . $_COOKIE['user'] . "->categories" .PHP_EOL;
        file_put_contents('logs/table.log', $message, FILE_APPEND);
        $stmt->execute([$name, $_GET['id_cat']]);
        $msg = 'Updated Successfully!';
    }

    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id_cat = ?');
    $stmt->execute([$_GET['id_cat']]);
    $cat = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$cat) {
        exit('Asset doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}
?>

<?=template_header('Read')?>

<div class="content update">
    <h2>Update Category #<?=$cat['id_cat']?></h2>
    <form action="cat_update.php?id_cat=<?=$cat['id_cat']?>" method="post">
        <label for="category_name">Name</label>
        <input type="text" name="category_name" value="<?=$cat['category_name']?>" id="category_name">
        <input type="submit" value="Update">
    </form>
    <?php if ($msg): ?>
        <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
