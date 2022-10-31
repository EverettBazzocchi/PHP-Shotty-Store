<header>
    <h1>Shotty Store</h1>
    <nav>
        <ul id="nav-list">
            <li><a href="./index.php">Home</a></li>
            <li><a href="./products.php">Products</a></li>
            <li><a href="./cart.php">Cart</a></li>
            <li><a href="./reviews.php">Reviews</a></li>
            <?php if (isset($_SESSION['user'])): ?>
            <li><a href="./logout.php">Logout</a></li>
            <?php else: ?>
            <li><a href="./login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>