<?php

require('./functions/api.php');

if (isset($_POST['name'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    $image = $_FILES['image'];

    $product = [
        'name' => $name,
        'price' => $price,
        'description' => $description,
        'category' => $category,
        'image' => $image
    ];

    createProduct($product, $_SESSION['user']['id']);
}

?>

<!DOCTYPE html>
<html lang="en">

<?php require('functions/head.php') ?>

<body>
    <?php require('functions/header.php') ?>
    <main id="create-product-page">
        <?php require('functions/store_header.php') ?>
        <form action="./create_product.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" name="price" id="price" step="any" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" cols="30" rows="10" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" name="image" id="image" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select name="category" id="category" required>
                    <option value="rank">Rank</option>
                    <option value="item">Item</option>
                </select>
            </div>

            <button type="submit">Create</button>
    </main>
</body>

</html>