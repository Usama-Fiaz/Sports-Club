<?php
session_start();
//datbase connection file
include('includes/config.php');
error_reporting(0);
// Code for Email Subscription
if(isset($_POST['subscribe']))
{

// Getting Post values
$emailid=$_POST['email'];   
// query for data insertion
$sql="INSERT INTO tblsubscriber(UserEmail) VALUES(:emailid)";
//preparing the query
$query = $dbh->prepare($sql);
//Binding the values
$query->bindParam(':emailid',$emailid,PDO::PARAM_STR);
//Execute the query
$query->execute();
//Check that the insertion really worked
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId)
{
echo "<script>alert('Success : Successfully subscribed');</script>";
echo "<script>window.location.href='index.php'</script>";  
}
else 
{
echo "<script>alert('Error : Something went wrong. Please try again');</script>";   
}

}

    ?>
<!doctype html>
<html class="no-js" lang="en">
    <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> SportsSync| Home Page </title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
        <!-- bootstrap v3.3.6 css -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <!-- animate css -->
        <link rel="stylesheet" href="css/animate.css">
        <!-- meanmenu css -->
        <link rel="stylesheet" href="css/meanmenu.min.css">
        <!-- owl.carousel css -->
        <link rel="stylesheet" href="css/owl.carousel.css">
        <!-- icofont css -->
        <link rel="stylesheet" href="css/icofont.css">
        <!-- Nivo css -->
        <link rel="stylesheet" href="css/nivo-slider.css">
        <!-- animaton text css -->
        <link rel="stylesheet" href="css/animate-text.css">
        <!-- Metrial iconic fonts css -->
        <link rel="stylesheet" href="css/material-design-iconic-font.min.css">
        <!-- style css -->
        <link rel="stylesheet" href="style.css">
        <!-- responsive css -->
        <link rel="stylesheet" href="css/responsive.css">
        <!-- color css -->
        <link href="css/color/skin-default.css" rel="stylesheet">
        
        <!-- modernizr css -->
        <script src="js/vendor/modernizr-2.8.3.min.js"></script>
        <style>
      

.home-02 .container,
.home-02 .container > .container {
  max-width: 100% !important;
  margin: 0 auto !important;
  padding-left: 0 !important;
  padding-right: 0 !important;
}

        </style>
    </head>
    <body>
         <!--body-wraper-are-start-->
         <div class="wrapper home-02">
         
            <!--slider header area are start-->
         <?php include_once('includes/header.php');?>
                <!-- header End-->

                    <!-- Carousel -->
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/gentrit-sylejmani-JjUyjE-oEbM-unsplash.jpg" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <h5 class="animate__animated animate__fadeInDown">Welcome to SportsSync</h5>
                    <p class="animate__animated animate__fadeInUp">Where Champions are Made</p>
                    <a href="all-events.php" class="btn btn-primary carousel-button animate__animated animate__fadeInUp" >Events</a>
                    <a href="signup.php" class="btn btn-secondary carousel-button animate__animated animate__fadeInUp" >Become a Member</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="images/josh-calabrese-zcYRw547Dps-unsplash.jpg" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <h5 class="animate__animated animate__zoomIn">Cutting-Edge Training Grounds</h5>
                    <p class="animate__animated animate__fadeInRight">Elevate your game with top-tier amenities and professional coaching.</p>
                    <a href="all-events.php" class="btn btn-primary carousel-button animate__animated animate__fadeInUp" >Events</a>
                    <a href="signup.php" class="btn btn-secondary carousel-button animate__animated animate__fadeInUp" >Become a Member</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="images/markus-spiske-BfphcCvhl6E-unsplash.jpg" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <h5 class="animate__animated animate__slideInLeft">Upcoming Summer Leagues</h5>
                    <p class="animate__animated animate__slideInRight">Register today and compete with the best.</p>
                    <a href="all-events.php" class="btn btn-primary carousel-button animate__animated animate__fadeInUp">Events</a>
                    <a href="signup.php" class="btn btn-secondary carousel-button animate__animated animate__fadeInUp">Become a Member</a>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    <div class="container-fluid p-0">
        <div class="sports-container">
            <!-- Cycling -->
            <div class="sport-card" data-aos="fade-up" data-aos-delay="50" onclick="window.location.href='cycling-page.html';">
                <img src="images/cycling.jpeg" alt="Cycling Background">
               
                <div class="sport-card-text">Cycling</div>
            </div>
            <!-- Golf -->
            <div class="sport-card" data-aos="fade-up" data-aos-delay="150" onclick="window.location.href='golf-page.html';">
                <img src="images/golf.jpeg" alt="Golf Background">
              
                <div class="sport-card-text">Golf</div>
            </div>
            <!-- Swimming -->
            <div class="sport-card" data-aos="fade-up" data-aos-delay="250" onclick="window.location.href='swimming-page.html';">
                <img src="images/swimming.jpeg" alt="Swimming Background">
               
                <div class="sport-card-text">Swimming</div>
            </div>
            <div class="sport-card" data-aos="fade-up" data-aos-delay="350" onclick="window.location.href='tennis-page.html';">
                <img src="images/tennis.jpeg" alt="Tennis Background">
               
                <div class="sport-card-text">Tennis</div>
            </div>
            <!-- Track Training -->
            <div class="sport-card" data-aos="fade-up" data-aos-delay="450" onclick="window.location.href='track-training-page.html';">
                <img src="images/soccer.jpeg" alt="Soccer">
                <div class="sport-card-text">Soccer</div>
            </div>
            <!-- Weight Training -->
            <div class="sport-card" data-aos="fade-up" data-aos-delay="550" onclick="window.location.href='weight-training-page.html';">
                <img src="images/basketball.jpeg" alt="Basketball">
                <div class="sport-card-text">BasketBall</div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="subscription-header" data-aos="fade-up">
            <h2>MEMBERSHIP PRIVILEGES</h2>
            <p>Exclusive Training Packages</p>
        </div>
        <div class="container">
            <div class="row">
                <!-- Card 1 -->
                <div class="col-md-4" data-aos="fade-right" data-aos-delay="100">
                <div class="card custom-card" onclick="<?php echo (isset($_SESSION['usrid']) && !empty($_SESSION['usrid'])) ? "window.location.href='all-training.php';" : "alertAndRedirect()"; ?>">
                        <img class="card-img-top" src="images/swimming-coach.jpeg" alt="Swimming Coach">
                        <div class="card-body">
                            <div>
                                <h5 class="card-title">SWIMMING COACH</h5>
                    <p class="card-text">Training Center</p>
                    <p class="card-text"></span>  215 Days</p>
                            </div>
                            <div class="card-arrow">&rarr;</div>
                        </div>
                    </div>
                </div>
        
                <!-- Card 2 -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card custom-card" onclick="<?php echo (isset($_SESSION['usrid']) && !empty($_SESSION['usrid'])) ? "window.location.href='all-training.php';" : "alertAndRedirect()"; ?>">
                        <img class="card-img-top" src="images/tennis-coach.jpeg" alt="Tennis Champion">
                        <div class="card-body">
                            <div>
                                <h5 class="card-title">TENNIS CHAMPION</h5>
                    <p class="card-text">Reputed Training</p>
                    <p class="card-text"></span>  6 Months</p>
                            </div>
                            <div class="card-arrow">&rarr;</div>
                        </div>
                    </div>
                </div>
        
                <!-- Card 3 -->
                
                <div class="col-md-4" data-aos="fade-left" data-aos-delay="300">
                <div class="card custom-card" onclick="<?php echo (isset($_SESSION['usrid']) && !empty($_SESSION['usrid'])) ? "window.location.href='all-training.php';" : "alertAndRedirect()"; ?>">
                        <img class="card-img-top" src="images/trecking-coach.jpeg" alt="Trekking Practice">
                        <div class="card-body">
                            <div>
                                <h5 class="card-title">TREKKING PRACTICE</h5>
                                <p class="card-text">By Well Experienced</p>
                                <p class="card-text"></span> 99 Days</p>
                            </div>
                            <div class="card-arrow">&rarr;</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
:root {
    --primary-color: #ff4757;
    --secondary-color: #2f3542;
    --bg-color: #1e272e;
    --text-color: #ffffff;
    --accent-color: #57606f;
}

body {
    font-family: 'Poppins', sans-serif;
    background: var(--bg-color);
    color: var(--text-color);
    margin: 0;
    padding: 0;
}

.container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

.section-title {
    text-align: center;
    font-size: 2.5rem;
    margin-bottom: 30px;
    color: var(--primary-color);
    font-weight: 700;
}

.events-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.event-card {
    background: var(--secondary-color);
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.3s ease-in-out;
    max-width: 350px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
}

.event-card:hover {
    transform: scale(1.05);
}

.event-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.event-info {
    padding: 20px;
    text-align: center;
}

.event-title {
    font-size: 1.5rem;
    margin-bottom: 10px;
    color: var(--text-color);
}

.event-date,
.event-location {
    font-size: 1rem;
    color: var(--accent-color);
}

.event-btn {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 20px;
    background: var(--primary-color);
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
    transition: background 0.3s;
}

.event-btn:hover {
    background: #e84118;
}
</style>

<style>
        /* body {
            font-family: Arial, sans-serif;
            background: #0D1117;
            color: #fff;
            text-align: center;
            margin: 0;
            padding: 50px 0;
        } */
        .counter-area {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .single-count {
            background: rgb(47, 53, 66);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.1);
            text-align: center;
            transition: transform 0.3s ease;
            width: 180px;
        }
        .single-count:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(255, 255, 255, 0.2);
        }
        .count-icon img {
            width: 60px;
            height: auto;
            margin-bottom: 15px;
        }
        h3 {
            font-size: 32px;
            font-weight: bold;
            margin: 10px 0;
            color: #58A6FF;
        }
        p {
            font-size: 18px;
            color: #C9D1D9;
            font-weight: 600;
        }
    </style>

                 <!--up comming events area-->
<!-- Upcoming Events Area -->
<div class="upcoming-events-area">
    <div class="container">
        <h1 class="section-title">Upcoming Events</h1>
        <div class="events-wrapper">
            <?php
            $isactive = 1;
            $sql = "SELECT EventName, EventLocation, EventStartDate, EventEndDate, EventImage, id FROM tblevents WHERE IsActive=:isactive ORDER BY id DESC LIMIT 5";
            $query = $dbh->prepare($sql);
            $query->bindParam(':isactive', $isactive, PDO::PARAM_STR);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);
            if ($query->rowCount() > 0) {
                foreach ($results as $row) {
            ?>
                <div class="event-card">
                    <div class="event-image">
                        <img src="admin/eventimages/<?php echo htmlentities($row->EventImage); ?>" alt="<?php echo htmlentities($row->EventName); ?>">
                    </div>
                    <div class="event-info">
                        <h2 class="event-title"> <?php echo htmlentities($row->EventName); ?> </h2>
                        <p class="event-date"> <?php echo htmlentities($row->EventStartDate); ?> - <?php echo htmlentities($row->EventEndDate); ?> </p>
                        <p class="event-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlentities($row->EventLocation); ?></p>
                        <div class="event-btn-wrapper">
                            <a href="event-details.php?evntid=<?php echo htmlentities($row->id); ?>" class="event-btn">View Details</a>
                        </div>
                    </div>
                </div>
            <?php } } ?>
        </div>
    </div>
</div>

            
<div class="counter-area">
        <div class="single-count">
            <div class="count-icon">
                <img src="img/icon/count-01.png" alt="">
            </div>
            <h3>50+</h3>
            <p>Events</p>
        </div>
        <div class="single-count">
            <div class="count-icon">
                <img src="img/icon/count-02.png" alt="">
            </div>
            <h3>19+</h3>
            <p>Locations</p>
        </div>
        <div class="single-count">
            <div class="count-icon">
                <img src="img/icon/count-03.png" alt="">
            </div>
            <h3>12+</h3>
            <p>Networks</p>
        </div>
        <div class="single-count">
            <div class="count-icon">
                <img src="img/icon/count-04.png" alt="">
            </div>
            <h3>90+</h3>
            <p>Countries</p>
        </div>
        <div class="single-count">
            <div class="count-icon">
                <img src="img/icon/count-05.png" alt="">
            </div>
            <h3>5</h3>
            <p>Live Telecasts</p>
        </div>
        <div class="single-count">
            <div class="count-icon">
                <img src="img/icon/count-06.png" alt="">
            </div>
            <h3>200+</h3>
            <p>Ideas</p>
        </div>
    </div>
            
            <!--call to action area start-->
            <div class="mt-3 pb100 pt85" style="background-color:rgb(51, 51, 51) !important;">
                <div class="container" style="background-color:rgb(51, 51, 51) !important;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="cal-to-wrap">
                                <h1 class="section-title">Enter Your Email Address For Events & News</h1>
                                <form method="post" name="subscribe">
                                    <div class="input-box">
                                        <input type="email" placeholder="Enter your E-mail Address" class="info" name="email" required="true"> 
                                        <button type="submit" name="subscribe" class="send-btn"><i class="zmdi zmdi-mail-send"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--call to action area End--> 

            <!--information area are start-->
           <?php include_once('includes/footer.php');?>
            <!--footer area are start-->
         </div>   
        <!--body-wraper-are-end-->
        
        <!--==== all js here====-->
        <script>
function alertAndRedirect() {
    alert("Please login first to access training sessions.");
    window.location.href = "signin.php";
}
</script>

        <!-- jquery latest version -->
        <script src="js/vendor/jquery-3.1.1.min.js"></script>
        <!-- bootstrap js -->
        <script src="js/bootstrap.min.js"></script>
        <!-- owl.carousel js -->
        <script src="js/owl.carousel.min.js"></script>
        <!-- meanmenu js -->
        <script src="js/jquery.meanmenu.js"></script>
        <!-- Nivo js -->
        <script src="js/nivo-slider/jquery.nivo.slider.pack.js"></script>
        <script src="js/nivo-slider/nivo-active.js"></script>
        <!-- wow js -->
        <script src="js/wow.min.js"></script>
        <!-- datepicker js -->
        <script src="js/bootstrap-datepicker.js"></script>
        <!-- waypoint js -->
        <script src="js/waypoints.min.js"></script>
        <!-- onepage nav js -->
        <script src="js/jquery.nav.js"></script>
        <!-- animate text JS -->
        <script src="js/animate-text.js"></script>
        <!-- plugins js -->
        <script src="js/plugins.js"></script>
        <!-- main js -->
        <script src="js/main.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.9.11/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
   
   <script>
    AOS.init({
      duration: 800,
      once: false, 
    });
  
   
    window.addEventListener('load', AOS.refresh);
  </script>
    </body>
</html>