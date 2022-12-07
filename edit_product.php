<?php
require('./functions/api.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else if (isset($_POST['id'])) {
    $id = $_POST['id'];
} else {
    header('Location: /project/products');
}

$product = getProduct($id);

if (!$product) {
    echo 'Product not found.';
}

if (!(isset($_SESSION['user']) && (($_SESSION['user']['role'] >= 4) || ($_SESSION['user']['id'] === $product['owner_id'])))) {
    echo 'You do not have permission to edit this product.';
}

if (isset($_POST['name'])) {
    if (!(isset($_SESSION['user']) && (($_SESSION['user']['role'] >= 4)))) {
        echo 'You do not have permission to edit this product6666.';
    }
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $id = $_POST['id'];

    $image = $_FILES['replace-image'];

    if ($image['name'] !== '') {
        $image = uploadImage($image);
    } else {
        $image = $product['image'];
    }

    $product = [
        'name' => $name,
        'price' => $price,
        'description' => $description,
        'category' => $category,
        'image' => $image,
        'id' => $id
    ];

    try{
        editProduct($product);
        header('Location: /project/product/' . $id);

    } catch (Exception $e) {
        echo $e->getMessage();
    }
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
            <form action="./edit_product.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" id="id" value="<?= $product['id'] ?>">
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
                        <option value="ranks" <?= $product['category']==='ranks' ? 'selected' : '' ?>>Rank</option>
                        <option value="items" <?= $product['category']==='items' ? 'selected' : '' ?>>Item</option>
                    </select>
                </div>

                <button type="submit">Edit</button>

        </div>
    </main>