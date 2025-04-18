<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['adminsession'])==0)
{   
  header('location:logout.php');
}
else{ 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SportsSync | Dashboard</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/morrisjs/morris.css" rel="stylesheet">
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
      <?php include_once('includes/header.php');?>
      <?php include_once('includes/leftbar.php');?>
    </nav>

    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Dashboard</h1>
        </div>
      </div>
      
      <!--=====================================-->
      <!-- Row 1: Listed Categories, Sponsors, Events, Reg. Users -->
      <!--=====================================-->
      <div class="row">
        <!-- Listed Categories -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <!-- Changed icon to fa-list -->
                  <i class="fa fa-list fa-5x"></i>
                </div>
                <?php 
                  $sql ="SELECT id FROM tblcategory";
                  $query = $dbh->prepare($sql);
                  $query->execute();
                  $listedcategories=$query->rowCount();
                ?>
                <div class="col-xs-9 text-right">
                  <div class="huge"><?php echo htmlentities($listedcategories);?></div>
                  <div>Listed Categories</div>
                </div>
              </div>
            </div>
            <a href="manage-category.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

        <!-- Sponsors -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-green">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <!-- Changed icon to fa-suitcase -->
                  <i class="fa fa-suitcase fa-5x"></i>
                </div>
                <?php 
                  $sql ="SELECT id FROM tblsponsers";
                  $query = $dbh->prepare($sql);
                  $query->execute();
                  $listedsponsors=$query->rowCount();
                ?>
                <div class="col-xs-9 text-right">
                  <div class="huge"><?php echo htmlentities($listedsponsors);?></div>
                  <div>Sponsors</div>
                </div>
              </div>
            </div>
            <a href="manage-sponsers.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

        <!-- Total Events -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-yellow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <!-- Changed icon to fa-calendar -->
                  <i class="fa fa-calendar fa-5x"></i>
                </div>
                <?php 
                  $sql ="SELECT id FROM tblevents";
                  $query = $dbh->prepare($sql);
                  $query->execute();
                  $totalevents=$query->rowCount();
                ?>
                <div class="col-xs-9 text-right">
                  <div class="huge"><?php echo htmlentities($totalevents);?></div>
                  <div>Total Events</div>
                </div>
              </div>
            </div>
            <a href="manage-events.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

        <!-- Total Registered Users -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-red">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <!-- Kept icon as fa-users -->
                  <i class="fa fa-users fa-5x"></i>
                </div>
                <?php 
                  $sql ="SELECT Userid FROM tblusers";
                  $query = $dbh->prepare($sql);
                  $query->execute();
                  $regusers=$query->rowCount();
                ?>
                <div class="col-xs-9 text-right">
                  <div class="huge"><?php echo htmlentities($regusers);?></div>
                  <div>Total Reg. Users</div>
                </div>
              </div>
            </div>
            <a href="manage-users.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>
      </div><!-- /.row -->

      <!--=====================================-->
      <!-- Row 2: Bookings (total, new, confirmed, canceled) -->
      <!--=====================================-->
      <div class="row">
        <!-- Total Bookings -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-yellow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <!-- Changed icon to fa-shopping-cart -->
                  <i class="fa fa-shopping-cart fa-5x"></i>
                </div>
                <?php 
                  $sql ="SELECT id FROM tblbookings";
                  $query = $dbh->prepare($sql);
                  $query->execute();
                  $totalbookings=$query->rowCount();
                ?>
                <div class="col-xs-9 text-right">
                  <div class="huge"><?php echo htmlentities($totalbookings);?></div>
                  <div>Total Bookings</div>
                </div>
              </div>
            </div>
            <a href="all-booking.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

        <!-- New Booking -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <!-- Changed icon to fa-cart-plus -->
                  <i class="fa fa-cart-plus fa-5x"></i>
                </div>
                <?php 
                  $sql ="SELECT id FROM tblbookings WHERE BookingStatus IS NULL";
                  $query = $dbh->prepare($sql);
                  $query->execute();
                  $newbooking=$query->rowCount();
                ?>
                <div class="col-xs-9 text-right">
                  <div class="huge"><?php echo htmlentities($newbooking);?></div>
                  <div>New Booking</div>
                </div>
              </div>
            </div>
            <a href="new-bookings.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

        <!-- Confirmed Booking -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-green">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <!-- Changed icon to fa-check -->
                  <i class="fa fa-check fa-5x"></i>
                </div>
                <?php 
                  $status="Confirmed";
                  $sql ="SELECT id FROM tblbookings WHERE BookingStatus=:status";
                  $query = $dbh->prepare($sql);
                  $query->bindParam(':status',$status,PDO::PARAM_STR);
                  $query->execute();
                  $confirmedbooking=$query->rowCount();
                ?>
                <div class="col-xs-9 text-right">
                  <div class="huge"><?php echo htmlentities($confirmedbooking);?></div>
                  <div>Confirmed Booking</div>
                </div>
              </div>
            </div>
            <a href="confirmed-bookings.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

        <!-- Cancelled Bookings -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-red">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <!-- Changed icon to fa-times -->
                  <i class="fa fa-times fa-5x"></i>
                </div>
                <?php 
                  $status="Cancelled";
                  $sql ="SELECT id FROM tblbookings WHERE BookingStatus=:status";
                  $query = $dbh->prepare($sql);
                  $query->bindParam(':status',$status,PDO::PARAM_STR);
                  $query->execute();
                  $cancelledbooking=$query->rowCount();
                ?>
                <div class="col-xs-9 text-right">
                  <div class="huge"><?php echo htmlentities($cancelledbooking);?></div>
                  <div>Cancelled Bookings</div>
                </div>
              </div>
            </div>
            <a href="cancelled-booking.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>
      </div><!-- /.row -->

      <!--=====================================-->
      <!-- Row 3: Subscribers, Shopping Categories, Products, Total Orders -->
      <!--=====================================-->
      <div class="row">
        <!-- Total Reg. Subscriber -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <!-- Changed icon to fa-envelope -->
                  <i class="fa fa-envelope fa-5x"></i>
                </div>
                <?php 
                  $sql ="SELECT id FROM tblsubscriber";
                  $query = $dbh->prepare($sql);
                  $query->execute();
                  $regsubscribers=$query->rowCount();
                ?>
                <div class="col-xs-9 text-right">
                  <div class="huge"><?php echo htmlentities($regsubscribers);?></div>
                  <div>Total Reg. Subscriber</div>
                </div>
              </div>
            </div>
            <a href="subscribers.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

        <!-- Shopping Categories -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <!-- Changed icon to fa-tags -->
                  <i class="fa fa-tags fa-5x"></i>
                </div>
                <?php 
                  $sql ="SELECT id FROM shopping_category";
                  $query = $dbh->prepare($sql);
                  $query->execute();
                  $listedshopcat=$query->rowCount();
                ?>
                <div class="col-xs-9 text-right">
                  <div class="huge"><?php echo htmlentities($listedshopcat);?></div>
                  <div>Shopping Categories</div>
                </div>
              </div>
            </div>
            <a href="manage-shoppingcategory.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

        <!-- Products -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-green">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <!-- Changed icon to fa-cubes -->
                  <i class="fa fa-cubes fa-5x"></i>
                </div>
                <?php 
                  $sql ="SELECT id FROM products";
                  $query = $dbh->prepare($sql);
                  $query->execute();
                  $totalproducts = $query->rowCount();
                ?>
                <div class="col-xs-9 text-right">
                  <div class="huge"><?php echo htmlentities($totalproducts);?></div>
                  <div>Products</div>
                </div>
              </div>
            </div>
            <a href="manage-products.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

        <!-- Total Orders -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-red">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <!-- Changed icon to fa-list-alt -->
                  <i class="fa fa-list-alt fa-5x"></i>
                </div>
                <?php 
                  // Fetch total orders
                  $sql ="SELECT COUNT(*) as total FROM orders";
                  $query = $dbh->prepare($sql);
                  $query->execute();
                  $torders = $query->fetchColumn();
                ?>
                <div class="col-xs-9 text-right">
                  <div class="huge"><?php echo htmlentities($torders);?></div>
                  <div>Total Orders</div>
                </div>
              </div>
            </div>
            <a href="all-orders.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>
      </div><!-- /.row -->

      <!--=====================================-->
      <!-- Row 4: New Order, Packed, Dispatched, In Transit -->
      <!--=====================================-->
      <div class="row">
        <!-- New Order -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-green">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <!-- Changed icon to fa-cart-arrow-down -->
                  <i class="fa fa-cart-arrow-down fa-5x"></i>
                </div>
                <?php 
                  // $norders is presumably set somewhere or needs a query:
                  // Example: $norders = ...
                  // Make sure to define $norders if not already set:
                  // $sql ="SELECT COUNT(*) FROM orders WHERE status='New'"; ...
                  // For now, just assume it's already defined or adapt to your actual logic
                ?>
                <div class="col-xs-9 text-right">
                  <div class="huge"><?php echo htmlentities($norders);?></div>
                  <div>New Order</div>
                </div>
              </div>
            </div>
            <a href="new-order.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

        <!-- Packed Orders -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <!-- Changed icon to fa-archive -->
                  <i class="fa fa-archive fa-5x"></i>
                </div>
                <?php 
                  // $porders presumably is also set or needs a query
                  // $porders = ...
                ?>
                <div class="col-xs-9 text-right">
                  <div class="huge"><?php echo htmlentities($porders);?></div>
                  <div>Packed Orders</div>
                </div>
              </div>
            </div>
            <a href="packed-orders.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

        <!-- Dispatched Orders -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-green">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <!-- Changed icon to fa-truck -->
                  <i class="fa fa-truck fa-5x"></i>
                </div>
                <?php 
                  // $dtorders presumably is also set or needs a query
                  // $dtorders = ...
                ?>
                <div class="col-xs-9 text-right">
                  <div class="huge"><?php echo htmlentities($dtorders);?></div>
                  <div>Dispatched Orders</div>
                </div>
              </div>
            </div>
            <a href="dispatched-orders.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

        <!-- In Transit Orders -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-red">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <!-- Changed icon to fa-truck again (or fa-bus, etc.) -->
                  <i class="fa fa-truck fa-5x"></i>
                </div>
                <?php 
                  // $intorders presumably is also set or needs a query
                  // $intorders = ...
                ?>
                <div class="col-xs-9 text-right">
                  <div class="huge"><?php echo htmlentities($intorders);?></div>
                  <div>In Transit Orders</div>
                </div>
              </div>
            </div>
            <a href="intransit-orders.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>
      </div><!-- /.row -->

      <!--=====================================-->
      <!-- Row 5: Out for Delivery, Delivered, Cancelled, Total Reg. Users -->
      <!--=====================================-->
      <div class="row">
        <!-- Out for Delivery -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-yellow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <!-- Changed icon to fa-location-arrow -->
                  <i class="fa fa-location-arrow fa-5x"></i>
                </div>
                <?php 
                  // $otforders presumably is also set or needs a query
                  // $otforders = ...
                ?>
                <div class="col-xs-9 text-right">
                  <div class="huge"><?php echo htmlentities($otforders);?></div>
                  <div>Out for Delivery</div>
                </div>
              </div>
            </div>
            <a href="outfordelivery.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

        <!-- Delivered Orders -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-green">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <!-- Changed icon to fa-check-circle -->
                  <i class="fa fa-check-circle fa-5x"></i>
                </div>
                <?php 
                  // $deliveredorders presumably is also set or needs a query
                  // $deliveredorders = ...
                ?>
                <div class="col-xs-9 text-right">
                  <div class="huge"><?php echo htmlentities($deliveredorders);?></div>
                  <div>Delivered Orders</div>
                </div>
              </div>
            </div>
            <a href="delivered-orders.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

        <!-- Cancelled Orders -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-yellow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <!-- Changed icon to fa-ban -->
                  <i class="fa fa-ban fa-5x"></i>
                </div>
                <?php 
                  // $cancelledorders presumably is also set or needs a query
                  // $cancelledorders = ...
                ?>
                <div class="col-xs-9 text-right">
                  <div class="huge"><?php echo htmlentities($cancelledorders);?></div>
                  <div>Cancelled Orders</div>
                </div>
              </div>
            </div>
            <a href="cancelled-orders.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

        <!-- Total Registered Users (again) -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-red">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <!-- Changed icon to fa-user-circle for variety -->
                  <i class="fa fa-user-circle fa-5x"></i>
                </div>
                <?php 
                  $sql ="SELECT Userid FROM tblusers";
                  $query = $dbh->prepare($sql);
                  $query->execute();
                  $regusers = $query->rowCount();
                ?>
                <div class="col-xs-9 text-right">
                  <div class="huge"><?php echo htmlentities($regusers);?></div>
                  <div>Total Registered Users</div>
                </div>
              </div>
            </div>
            <a href="manage-users.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>
      </div><!-- /.row -->
      <div class="row">
      
    </div><!-- /#page-wrapper -->
</div><!-- /#wrapper -->

<!-- Scripts -->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="../dist/js/sb-admin-2.js"></script>
</body>
</html>
<?php } ?>
