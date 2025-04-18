<?php
// Assuming session is already started and $dbh, $welcomeText, and $cartCount are set as in your code
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Build welcome text + cart count
$cartCount = 0;
if (isset($_SESSION['usrid']) && !empty($_SESSION['usrid'])) {
    $userName = $_SESSION['username'] ?? 'User';
    $userId = $_SESSION['usrid'];
    $cartSql = "SELECT COUNT(*) AS totalItems FROM cart WHERE userId = :uid";
    $cartStmt = $dbh->prepare($cartSql);
    $cartStmt->bindParam(':uid', $userId, PDO::PARAM_INT);
    $cartStmt->execute();
    $cartRow = $cartStmt->fetch(PDO::FETCH_OBJ);
    if ($cartRow && $cartRow->totalItems) {
        $cartCount = $cartRow->totalItems;
    }
}
$ret = "SELECT SiteName FROM tblgenralsettings";
$querys = $dbh->prepare($ret);
$querys->execute();
$resultss = $querys->fetchAll(PDO::FETCH_OBJ);
?>
<?php if ($querys->rowCount() > 0) {
    foreach ($resultss as $rows) { ?>
<div id="home" class="header-slider-area">
    <div class="header-area header-2">
        <div id="sticker" class="logo-menu-area header-area-2">
            <div class="container hidden-xs">
                <div class="row">
                    <div class="col-md-2 col-sm-3">
                        <div class="logo">
                            <a href="index.php" style="font-size:42px; color:#fff">
                                <?php echo htmlentities($rows->SiteName); ?>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-9">
                        <div class="main-menu text-right">
                            <nav>
                                <ul id="nav">
                                    <li><a class="smooth-scroll" href="index.php">Home</a></li>
                                    <li class="dropdown">
                                        <a href="about-us.php">About Us</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="clubs.php">Clubs</a></li>
                                            <li><a href="trainers.php">Trainers</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown">
                                        <a href="about-us.php">Sports</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="all-events.php">Events</a></li>
                                            <li>
    <?php if (isset($_SESSION['usrid']) && !empty($_SESSION['usrid'])) { ?>
        <a href="all-training.php">Training</a>
    <?php } else { ?>
        <a href="#" onclick="alertAndRedirect()">Training</a>
    <?php } ?>
</li>
                                        </ul>
                                    </li>

                                    <!-- Adaptive Sports Dropdown -->
                                    <li class="dropdown">
                                        <a href="#">Adaptive Sports</a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="<?php echo (isset($_SESSION['usrid']) && !empty($_SESSION['usrid'])) ? 'category-wise-events.php?catid=8' : 'category-wise-events.php?catid=8'; ?>">
                                                    Events
                                                </a>
                                            </li>
                                            <li>
                                            <?php if (isset($_SESSION['usrid']) && !empty($_SESSION['usrid'])) { ?>
    <a href="category-wise-training.php?catid=1#">Training</a>
<?php } else { ?>
    <a href="#" onclick="alertAndRedirect()">Training</a>
<?php } ?>
</li>

                                        </ul>
                                    </li>
                                    <!-- End Adaptive Sports Dropdown -->
                                    <!-- Pure CSS hover dropdown for Shop -->
                                    <li class="dropdown">
                                        <a href="#">Shop</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="merchendise.php">All Products</a></li>
                                            <li><a href="shop-categories.php">Category Wise Products</a></li>
                                        </ul>
                                    </li>
                                    <li><a class="smooth-scroll" href="news.php">News</a></li>

                                    <?php if (empty($_SESSION['usrid'])) { ?>
                                        <li class="dropdown">
                                            <a href="#">Register</a>
                                            <ul class="dropdown-menu">
                                                <li><a href="signup.php">User</a></li>
                                                <li><a href="register-trainer.php">Trainer</a></li>
                                                <li><a href="register-club.php">Club</a></li>
                                            </ul>
                                        </li>
                                        <li><a class="smooth-scroll" href="signin.php">Login</a></li>
                                    <?php } else { ?>
                                        <li><a class="smooth-scroll" href="profile.php">My Account</a></li>
                                    <?php } ?>

                                    <!-- Single <li> for welcome + cart, floated right -->
                                    <li style="margin-left: auto; color:#fff;">
                                        <?php if (!empty($welcomeText)) { ?>
                                            <span style="margin-right:15px;">
                                                <?php echo $welcomeText; ?>
                                            </span>
                                        <?php } ?>
                                        <a class="smooth-scroll" href="my-cart.php" style="color:#fff;">
                                            <i class="fa fa-shopping-cart"></i>
                                            Cart (<?php echo $cartCount; ?>)
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div><!--logo menu area end-->
            <!-- mobile-menu-area start -->
            <div class="mobile-menu-area">
                <div class="container">
                    <div class="logo-02">
                        <a href="index.php" style="font-size:42px; color:#fff">
                            <?php echo htmlentities($rows->SiteName); ?>
                        </a>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <nav id="dropdown">
                                <ul>
                                    <li><a class="smooth-scroll" href="index.php">Home</a></li>
                                    <li><a class="smooth-scroll" href="about-us.php">About Us</a></li>
                                    <li><a class="smooth-scroll" href="all-events.php">Events</a></li>
                                    <!-- Adaptive Sports Dropdown for Mobile -->
                                    <li class="dropdown">
                                        <a href="#">Adaptive Sports</a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="<?php echo (isset($_SESSION['usrid']) && !empty($_SESSION['usrid'])) ? 'adaptive-events.php' : 'signin.php'; ?>">
                                                    Events
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo (isset($_SESSION['usrid']) && !empty($_SESSION['usrid'])) ? 'adaptive-training.php' : 'signin.php'; ?>">
                                                    Training
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <!-- End Adaptive Sports Dropdown -->
                                    <li><a class="smooth-scroll" href="news.php">News</a></li>
                                    <?php if (empty($_SESSION['usrid'])) { ?>
                                        <li><a class="smooth-scroll" href="signup.php">Signup</a></li>
                                        <li><a class="smooth-scroll" href="signin.php">Login</a></li>
                                    <?php } else { ?>
                                        <li><a class="smooth-scroll" href="profile.php">My Account</a></li>
                                        <li><?php echo $welcomeText; ?></li>
                                    <?php } ?>
                                    <li>
                                        <a class="smooth-scroll" href="my-cart.php">
                                            Cart (<?php echo $cartCount; ?>)
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!--mobile menu area end-->
        </div>
</div>
<?php
    }
} // end foreach $resultss
?>
<!-- ========== JavaScript ========== -->
<script>
function alertAndRedirect() {
    alert("Please login first to access training sessions.");
    window.location.href = "signin.php";
}
</script>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!-- Simple CSS for the dropdown hover -->
<style>
.dropdown {
  position: relative;
}
.dropdown .dropdown-menu {
  display: none;
  position: absolute;
  top: 100%;
  left: 0;
  background: #333;
  padding: 10px;
  z-index: 999;
  list-style: none;
}
.dropdown .dropdown-menu li {
  display: block;
}
.dropdown .dropdown-menu li a {
  display: block;
  color: #fff;
  text-decoration: none;
  padding: 5px 10px;
}
.dropdown .dropdown-menu li a:hover {
  background: #444;
}
.dropdown:hover .dropdown-menu {
  display: block;
}
</style>
