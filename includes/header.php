<header>
    <h1 class="logo">HOTEL</h1>
    <button class="hamburger">
        <i class="fa-solid fa-bars"></i>
    </button>
    
    <nav>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="rooms.php">Rooms</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="about.php">About</a></li>
        </ul>

        <div class='mobile-cta'>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href='account.php'><button><i class='fa-solid fa-user input-icon'></i> Account</button></a>
            <?php else: ?>
                <a href='login.php'><button><i class='fa-solid fa-user input-icon'></i> Login</button></a>
            <?php endif; ?>
        </div>
    </nav>

    <a class='cta' href='<?php echo isset($_SESSION['user_id']) ? "account.php" : "login.php"; ?>'>
        <button><i class='fa-solid fa-user input-icon'></i> 
            <?php echo isset($_SESSION['user_id']) ? "Account" : "Login"; ?>
        </button>
    </a>
</header>
