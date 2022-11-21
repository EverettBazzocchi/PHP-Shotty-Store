<?php

require('./functions/api.php');

if (isset($_GET['cat'])) {
    if ($_GET['cat'] == '') {
        $category = 'all';
    } else {
        $category = $_GET['cat'];
    }
} else {
    $category = "all";
}

?>

<!DOCTYPE html>
<html lang="en">

<?php require('functions/head.php') ?>

<body>
    <?php require('functions/header.php') ?>
    <main id="products-page">
        <?php require('functions/store_header.php') ?>
            <?php if (isset($_SESSION['user'])):
            if ($_SESSION['user']['role'] >= 4): ?>
            <h2><a class="create-item-link" href="/project/create_product">Create Item</a></h2>
            <?php endif ?>
            <?php endif ?>
            <div id="products-wrapper">
                <?php for ($i = 0; $i < 5; $i++) { ?>
                <?php foreach (getProducts($category) as $product): ?>
                    <?php if ($product['blocked'] == 0): ?>
                <a href="/project/product/<?= $product['id'] ?>">
                    <div class="product-item"
                        style="background-image: url(/project/uploads/images/<?= $product['image'] ?>)">

                        <div class="blur">

                            <h3 class="product-item-title">
                                <?= $product['name'] ?>
                            </h3>
                            <p class="product-item-price">
                                <?= $product['price'] ?>
                            </p>

                        </div>
                    </div>
                </a>
                <?php endif ?>
                <?php endforeach; ?>
                <?php } ?>

            </div>

    </main>
</body>

</html>