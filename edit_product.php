<?php
require('./functions/api.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    header('Location: /project/products');
}

$product = getProduct($id);

if (!$product) {
    header('Location: /project/products');
}

if (!(isset($_SESSION['user']) && (($_SESSION['user']['role'] >= 5) || ($_SESSION['user']['id'] === $product['owner_id'])))) {
    header('Location: /project/products');
}

if (isset($_POST['name'])) {
    if (!(isset($_SESSION['user']) && (($_SESSION['user']['role'] >= 5)))) {
        header('Location: /project/products');
    }
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $id = $_POST['id'];

    $image = $_FILES['replace-image'];

    $product = [
        'name' => $name,
        'price' => $price,
        'description' => $description,
        'category' => $category,
        'image' => $image,
        'id' => $id
    ];

    editProduct($product);
}


?>

<!DOCTYPE html>
<html lang="en">

<?php require($_SERVER['DOCUMENT_ROOT'] . '/project/functions/head.php') ?>

<body>
    <?php require('functions/header.php') ?>
    <main id="create-product-page">
        <?php require('functions/store_header.php') ?>
        <div class="container">
            <form action="./functions/edit_product.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" value="<?= $product['name'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" name="price" id="price" step="any" value="<?= $product['price'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" cols="30" rows="10"
                        required><?= $product['description'] ?></textarea>
                </div>
                <div class="form-group">
                    <label for="replace-image">Replacement Image? (1:1 aspect ratio for best quality)</label>
                    <input type="file" name="replace-image" id="replace-image">
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <select name="category" id="category" required>
                        <option value="rank" <?= $product['category']==='rank' ? 'selected' : '' ?>>Rank</option>
                        <option value="item" <?= $product['category']==='item' ? 'selected' : '' ?>>Item</option>
                    </select>
                </div>

                <button type="submit">Edit</button>

        </div>
    </main>