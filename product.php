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

var_dump($product);

if ($product['blocked'] === '1') {
    header('Location: /project/products');
}

?>


<!DOCTYPE html>
<html lang="en">


<?php require('functions/head.php') ?>

<body>
    <?php require('functions/header.php') ?>
    <main id="product-page">
        <?php if($product['blocked'] === '0'): ?>
        <div id="product-wrapper">
            <?php if(isset($_SESSION['user']) && ($_SESSION['user']['role'] >= 5 || $_SESSION['user']['id'] === $product['owner_id'])): ?>
            <form action="/project/functions/delete_product">
                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                <input type="submit" value="Delete">
            </form>
            <form action="/project/edit_product">
                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                <input type="submit" value="Edit">
            </form>
            <?php endif; ?>
            <div id="product-image-wrapper">
                <img src="/project/uploads/images/<?= $product['image'] ?>" alt="">
            </div>
            <div id="product-info-wrapper">
                <h1 id="product-title">
                    <?= $product['name'] ?>
                </h1>
                <p id="product-price">
                    <?= $product['price'] ?>
                </p>
                <p id="product-description">
                    <?= $product['description'] ?>
                </p>
                <div id="product-buttons">
                    <a href="/project/add_to_cart.php?id=<?= $product['id'] ?>" class="add-to-cart">Add to Cart</a>
                </div>
            </div>
        </div>
        <div id="product-reviews-wrapper">
            <h2>Reviews</h2>
            <?php foreach (getReviews($product['id']) as $review): ?>
            <div class="review">
                <p class="review-title">
                    <?= $review['title'] ?>
                </p>
                <p class="review-content">
                    <?= $review['content'] ?>
                </p>
                <p class="review-rating">
                    <?= $review['rating'] ?>
                </p>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
    <?php endif; ?>

</body>

</html>