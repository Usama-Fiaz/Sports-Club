<?php
session_start();
error_reporting(0);
include('includes/config.php'); // ensure $dbh is your PDO connection

// If user not logged in, redirect
if (strlen($_SESSION['usrid']) == 0) {
    header('location:logout.php');
    exit;
} else {
    // 1) Get user ID from session
    $uid = $_SESSION['usrid'];

    // 2) Handle "Join Another Club" form submission
    if (isset($_POST['joinClub'])) {
        $selectedClubId = intval($_POST['clubId']);

        // Insert into tblclubmembers (if not already joined)
        // Optionally, you can check if user already in that club.
        $sqlCheck = "SELECT COUNT(*) FROM tblclubmembers WHERE userId = :uid AND clubId = :cid";
        $stmtCheck = $dbh->prepare($sqlCheck);
        $stmtCheck->bindParam(':uid', $uid, PDO::PARAM_INT);
        $stmtCheck->bindParam(':cid', $selectedClubId, PDO::PARAM_INT);
        $stmtCheck->execute();
        $alreadyJoined = $stmtCheck->fetchColumn();

        if ($alreadyJoined > 0) {
            // They already joined
            echo "<script>alert('You have already joined this club.');</script>";
        } else {
            // Insert membership
            $sqlInsert = "INSERT INTO tblclubmembers (userId, clubId, joinedDate)
                          VALUES (:uid, :cid, NOW())";
            $stmtInsert = $dbh->prepare($sqlInsert);
            $stmtInsert->bindParam(':uid', $uid, PDO::PARAM_INT);
            $stmtInsert->bindParam(':cid', $selectedClubId, PDO::PARAM_INT);
            if ($stmtInsert->execute()) {
                echo "<script>alert('Successfully joined the club!');</script>";
            } else {
                echo "<script>alert('Error: Could not join the club.');</script>";
            }
        }
    }
// Handle "Leave Club" form submission
if (isset($_POST['leaveClub'])) {
    $clubIdToLeave = intval($_POST['leaveClubId']);

    $sqlDelete = "DELETE FROM tblclubmembers WHERE userId = :uid AND clubId = :cid";
    $stmtDelete = $dbh->prepare($sqlDelete);
    $stmtDelete->bindParam(':uid', $uid, PDO::PARAM_INT);
    $stmtDelete->bindParam(':cid', $clubIdToLeave, PDO::PARAM_INT);

    if ($stmtDelete->execute()) {
        echo "<script>alert('You have left the club successfully.');</script>";
    } else {
        echo "<script>alert('Error while leaving the club.');</script>";
    }
}

    // 3) Fetch the clubs the user is currently in
    $sql = "SELECT 
                c.id AS clubId,
                c.clubName,
                c.clubDescription,
                c.clubEmail,
                c.clubContact,
                c.clubAddress,
                c.clubLogo,
                cM.joinedDate
            FROM tblclubmembers cM
            JOIN tblclub c ON cM.clubId = c.id
            WHERE cM.userId = :uid
            ORDER BY cM.joinedDate DESC";
    $query = $dbh->prepare($sql);
    $query->bindParam(':uid', $uid, PDO::PARAM_INT);
    $query->execute();
    $joinedClubs = $query->fetchAll(PDO::FETCH_OBJ);

    // 4) Fetch clubs user is NOT in (for the dropdown)
    $sqlAll = "SELECT id, clubName
               FROM tblclub
               WHERE id NOT IN (
                   SELECT clubId FROM tblclubmembers WHERE userId = :uid
               )
               ORDER BY clubName ASC";
    $stmtAll = $dbh->prepare($sqlAll);
    $stmtAll->bindParam(':uid', $uid, PDO::PARAM_INT);
    $stmtAll->execute();
    $availableClubs = $stmtAll->fetchAll(PDO::FETCH_OBJ);
    ?>
    <!doctype html>
    <html class="no-js" lang="en">
    <head>
        <title>SportsSync | My Club</title>
        <!-- ========== Your CSS Includes ========== -->
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
        <link rel="stylesheet" href="css/faicons.css">
        <link href="css/color/skin-default.css" rel="stylesheet">
        <script src="js/vendor/modernizr-2.8.3.min.js"></script>

        <style>
            /* Optional custom styling for the club logo in table */
            .club-logo {
                width: 60px; 
                height: auto; 
                border: 1px solid #ccc;
            }
            .btn-sm {
    padding: 5px 10px;
    font-size: 12px;
    border-radius: 3px;
}

        </style>
    </head>
    <body>
    <div class="wrapper single-blog">
        <!-- Header area -->
        <div id="home" class="header-slider-area">
            <?php include_once('includes/header.php'); ?>
        </div>

        <!-- Breadcumb area -->
        <div class="breadcumb-area bg-overlay" style="background: #333; background-image: none;">
            <div class="container">
                <ol class="breadcrumb">
                    <li><a href="index.php">Home</a></li>
                    <li class="active">My Club</li>
                </ol>
            </div>
        </div>

        <!-- Main Content -->
        <div class="single-blog-area ptb100 fix">
            <div class="container">
                <div class="row">
                    <!-- Left side: user account nav (like “myaccountbar.php”) -->
                    <?php include_once('includes/myaccountbar.php'); ?>

                    <!-- Right side: main content -->
                    <div class="col-md-8 col-sm-7">
                        <div class="single-blog-body">
                            <div class="Leave-your-thought mt50">
                                <h3 class="aside-title uppercase">My Club</h3>

                                <!-- Form to join another club -->
                                <?php if (count($availableClubs) > 0): ?>
                                    <div class="row" style="margin-bottom:20px;">
                                        <div class="col-md-12">
                                            <form method="post">
                                                <label for="clubId">Join Another Club:</label>
                                                <select name="clubId" id="clubId" class="form-control" style="max-width:300px; display:inline-block;" required>
                                                    <option value="">--Select Club--</option>
                                                    <?php foreach($availableClubs as $aclub): ?>
                                                        <option value="<?php echo $aclub->id; ?>">
                                                            <?php echo htmlentities($aclub->clubName); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button type="submit" name="joinClub" class="btn btn-primary">Join</button>
                                            </form>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p>You have already joined all available clubs or no clubs exist yet.</p>
                                <?php endif; ?>

                                <div class="row">
                                    <div class="col-md-12 col-sm-6 col-xs-12 lyt-left">
                                        <div class="input-box leave-ib">
                                            <div class="table-responsive">
                                                <table border="2" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Logo</th>
                                                            <th>Club Name</th>
                                                            <th>Description</th>
                                                            <th>Contact</th>
                                                            <th>Joined On</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if (count($joinedClubs) > 0) {
                                                            $cnt = 1;
                                                            foreach ($joinedClubs as $row) {
                                                                ?>
                                                                <tr>
    <td><?php echo htmlentities($cnt); ?></td>
    <td>
        <?php if(!empty($row->clubLogo)): ?>
            <img src="clubimages/<?php echo htmlentities($row->clubLogo); ?>" alt="Club Logo" class="club-logo">
        <?php else: ?>
            <img src="images/placeholder.png" alt="Club Logo" class="club-logo">
        <?php endif; ?>
    </td>
    <td><?php echo htmlentities($row->clubName); ?></td>
    <td><?php echo htmlentities($row->clubDescription); ?></td>
    <td>
        Email: <?php echo htmlentities($row->clubEmail); ?><br>
        Phone: <?php echo htmlentities($row->clubContact); ?><br>
        Address: <?php echo htmlentities($row->clubAddress); ?>
    </td>
    <td><?php echo htmlentities($row->joinedDate); ?></td>
    <td> <!-- ✅ NEW ACTION COLUMN -->
        <?php if (count($joinedClubs) > 1): ?>
            <form method="post">
                <input type="hidden" name="leaveClubId" value="<?php echo $row->clubId; ?>">
                <button type="submit" name="leaveClub" class="btn btn-danger btn-sm"
                    onclick="return confirm('Are you sure you want to leave this club?');">
                    Leave Club
                </button>
            </form>
        <?php else: ?>
            <span class="text-muted">Must stay in at least one club</span>
        <?php endif; ?>
    </td>
</tr>

                                                               
                                                                <?php
                                                                $cnt++;
                                                            }
                                                        } else {
                                                            // The user did not join any clubs
                                                            echo "<tr><td colspan='6'>You have not registered with any club.</td></tr>";
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div><!-- /table-responsive -->
                                        </div><!-- /input-box -->
                                    </div><!-- /.col-md-12 -->
                                </div><!-- /.row -->
                            </div><!-- /.Leave-your-thought -->
                        </div><!-- /.single-blog-body -->
                    </div><!-- /.col-md-8 -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </div><!-- /.single-blog-area -->

        <!-- Footer -->
        <?php include_once('includes/footer.php'); ?>
    </div><!-- /wrapper -->

    <!-- ====== JS Files ====== -->
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
    <?php
} // end else
?>
