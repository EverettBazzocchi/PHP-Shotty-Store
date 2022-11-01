<header>
    <div id="TopNav">
        <h1>Shotty Tech / Gaming</h1>
        <?php if (isset($_SESSION['user'])): ?>
            <h2>Welcome, <?= $_SESSION['user']['email'] ?><a class="logout" href="./functions/logout.php">Logout</a></h2>
        <?php else: ?>
        <h2><a class="" href="./login.php">Store Login</a></h2>
        <?php endif; ?>
    </div>
    <div id="Banner">
        <div id="Blur"><img id="Logo" src="https://assets.darklordbazz.com/img/shottyAssets/logo-back.png" alt="logo">
        </div>

    </div>
    <nav>
        <ul class="nav" id="left-nav">
            <a class="" href="https://www.shotty.tech/">
                <li>home</li>
            </a>
            <a class="" href="https://www.shotty.tech/Servers">
                <li>servers</li>
            </a>
            <a class="" href="https://www.shotty.tech/Staff">
                <li>staff</li>
            </a>
            <a class="" href="https://www.shotty.tech/Fun">
                <li>fun stuff</li>
            </a>
        </ul>
        <ul class="nav active" id="right-nav">
            <a class="" href="https://www.shotty.tech/Rules">
                <li>rules</li>
            </a>
            <a class="active" href="https://ihatethis.website/project">
                <li>store</li>
            </a>
            <a class="" href="https://www.shotty.tech/Changelog">
                <li>changelog</li>
            </a>
        </ul>
    </nav>
</header>