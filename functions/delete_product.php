<?php
require('./api.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    header('Location: /project/products');
}

$product = getProduct($id);

if (!$product) {
    header('Location: /project/products');
}

if (isset($_SESSION['user']) && (($_SESSION['user']['role'] >= 5) || ($_SESSION['user']['id'] === $product['owner_id']))) {
    try {
        deleteProduct($id);
        header('Location: /project/products');
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

?>