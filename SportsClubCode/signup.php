<?php
session_start();
error_reporting(0);
include('includes/config.php'); // PDO connection in $dbh

// SIGNUP process
if (isset($_POST['signup'])) {
    // 1) Gather POST values
    $fname     = $_POST['name'];
    $uname     = $_POST['username'];
    $emailid   = $_POST['email'];
    $pnumber   = $_POST['phonenumber'];
    $gender    = $_POST['gender'];
    $password  = md5($_POST['pass']);
    $clubId    = intval($_POST['clubId']); // new: the chosen club ID
    $status    = 1; // active user?

    try {
        // 2) Insert into tblusers
        $sql = "INSERT INTO tblusers
                (FullName, UserName, Emailid, PhoneNumber, UserGender, UserPassword, IsActive)
                VALUES
                (:fname, :uname, :emailid, :pnumber, :gender, :password, :status)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':fname',    $fname,    PDO::PARAM_STR);
        $query->bindParam(':uname',    $uname,    PDO::PARAM_STR);
        $query->bindParam(':emailid',  $emailid,  PDO::PARAM_STR);
        $query->bindParam(':pnumber',  $pnumber,  PDO::PARAM_STR);
        $query->bindParam(':gender',   $gender,   PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->bindParam(':status',   $status,   PDO::PARAM_INT);
        $query->execute();

        // 3) Check last inserted user ID
        $lastInsertId = $dbh->lastInsertId();
        if ($lastInsertId) {
            // 4) If user selected a club (clubId > 0), insert into tblclubmembers
            if ($clubId > 0) {
                $sqlClub = "INSERT INTO tblclubmembers (clubId, userId, joinedDate)
                            VALUES (:clubId, :userId, NOW())";
                $stmtClub = $dbh->prepare($sqlClub);
                $stmtClub->bindParam(':clubId', $clubId, PDO::PARAM_INT);
                $stmtClub->bindParam(':userId', $lastInsertId, PDO::PARAM_INT);
                $stmtClub->execute();
            }

            echo "<script>alert('Success : User signup successful. Now you can sign in.');</script>";
            echo "<script>window.location.href='signin.php'</script>";
            exit;
        } else {
            echo "<script>alert('Error : Something went wrong. Please try again');</script>";
        }
    } catch (Exception $e) {
        // handle or log error
        echo "<script>alert('Exception: " . addslashes($e->getMessage()) . "');</script>";
    }
}
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <title>SportsSync | User Signup</title>
    <!-- (Your existing CSS/JS includes) -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/meanmenu.min.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/icofont.css">
    <link rel="stylesheet" href="css/nivo-slider.css">
    <link rel="stylesheet" href="css/animate-text.css">
    <link rel="stylesheet" href="css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link href="css/color/skin-default.css" rel="stylesheet">
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>

    <script>
    // (Optional) Ajax check for username availability
    function checkusernameAvailability() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "check_availability.php",
            data: 'uname=' + $("#username").val(),
            type: "POST",
            success: function(data){
                $("#username-availabilty-status").html(data);
                $("#loaderIcon").hide();
            },
            error:function (){}
        });
    }
    </script>
</head>
<body>
<div class="wrapper single-blog">
    <!-- Header -->
    <div id="home" class="header-slider-area">
        <?php include_once('includes/header.php'); ?>
    </div>
    <!-- /header end -->

    <!-- Breadcumb -->
    <div class="breadcumb-area bg-overlay" style="background: #333; background-image: none;">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Signup</li>
            </ol>
        </div>
    </div>
    <!-- /breadcumb-area end -->

    <!-- Main Blog Area -->
    <div class="single-blog-area ptb100 fix">
        <div class="container">
            <div class="row">
                <!-- SIGNUP FORM -->
                <div class="col-md-8 col-sm-7">
                    <div class="single-blog-body">
                        <div class="Leave-your-thought mt50">
                            <h3 class="aside-title uppercase">User Signup</h3>
                            <div class="row">
                                <form name="signup" method="post">
                                    <div class="col-md-12 col-sm-6 col-xs-12 lyt-left">
                                        <div class="input-box leave-ib">
                                            <input type="text" placeholder="Name" class="info" name="name" required>
                                            
                                            <input type="text" placeholder="Username" class="info" name="username"
                                                   id="username" required onBlur="checkusernameAvailability()">
                                            <span id="username-availabilty-status" style="font-size:14px;"></span>
                                            
                                            <input type="email" placeholder="Email Id" class="info" name="email" required>
                                            <input type="tel" placeholder="Phone Number" pattern="[0-9]{10}"
                                                   title="10 numeric characters only" class="info" name="phonenumber"
                                                   maxlength="10" required>
                                            
                                            <select class="info" name="gender" required>
                                                <option value="">Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Transgender">Transgender</option>
                                            </select>
                                            
                                            <input type="password" name="pass" placeholder="Password"
                                                   pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"
                                                   title="at least one number and one uppercase and lowercase letter, and at least 6 or more characters"
                                                   class="info" required>
                                            <span style="font-size:11px; color:red">
                                                Password must contain at least one number, one uppercase, one lowercase, and be 6+ characters
                                            </span>
                                            
                                            <!-- NEW: Club Select (Optional) -->
                                            <select class="info" name="clubId">
                                                <option value="0">-- Register with a Club? (Optional) --</option>
                                                <?php
                                                // fetch active clubs from DB
                                                $sqlClubs = "SELECT id, clubName FROM tblclub WHERE IsActive = 1 ORDER BY clubName ASC";
                                                $stmtClubs = $dbh->prepare($sqlClubs);
                                                $stmtClubs->execute();
                                                $clubs = $stmtClubs->fetchAll(PDO::FETCH_OBJ);
                                                if ($stmtClubs->rowCount() > 0) {
                                                    foreach ($clubs as $club) {
                                                        echo '<option value="'.$club->id.'">'.htmlentities($club->clubName).'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xs-12 mt10">
                                        <div class="input-box post-comment">
                                            <input type="submit" value="Submit" id="signup" name="signup"
                                                   class="submit uppercase">
                                        </div>
                                    </div>

                                    <div class="col-xs-12 mt30">
                                        <div class="input-box post-comment" style="color:blue;">
                                            Already Registered? <a href="signin.php"> Signin here</a>
                                        </div>
                                    </div>
                                </form>
                            </div><!-- row -->
                        </div><!-- .Leave-your-thought -->
                    </div><!-- .single-blog-body -->
                </div><!-- .col-md-8 -->

           
            
            </div><!-- .row -->
        </div><!-- .container -->
    </div><!-- .single-blog-area -->

    <!-- Footer -->
    <?php include_once('includes/footer.php'); ?>
    <!-- /footer area end -->
</div>
<!-- /wrapper end -->

<!-- ====== JS Files (like your other pages) ====== -->
<script src="js/vendor/jquery-3.1.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/jquery.meanmenu.js"></script>
<script src="js/nivo-slider/jquery.nivo.slider.pack.js"></script>
<script src="js/nivo-slider/nivo-active.js"></script>
<script src="js/wow.min.js"></script>
<script src="js/jquery.mb.YTPlayer.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/waypoints.min.js"></script>
<script src="js/jquery.nav.js"></script>
<script src="js/animate-text.js"></script>
<script src="js/plugins.js"></script>
<script src="js/main.js"></script>
</body>
</html>
