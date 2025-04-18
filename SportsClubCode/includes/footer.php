
<?php
$ret = "Select  PhoneNumber,address,EmailId,footercontent from tblgenralsettings ";
$querys = $dbh -> prepare($ret);
$querys->execute();
$resultss=$querys->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($querys->rowCount() > 0)
{
foreach($resultss as $rows)
{ ?>
<style>
    /* ========== Information Area Improvements ========== */

/* Give the information area a soft background */
.information-area.off-white {
  background: #f9f9f9; /* or a color that fits your theme */
  padding-top: 50px;
  padding-bottom: 50px;
}

/* Each "single-information" block as a card-like container */
.single-information {
  background: #fff;
  border: 1px solid #eee;
  border-radius: 6px;
  margin-bottom: 20px;
  padding: 20px;
  transition: box-shadow 0.3s ease, transform 0.3s ease;
}

/* Hover effect to lift each block slightly */
.single-information:hover {
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  transform: translateY(-3px);
}

/* Center text properly, ensure icons are bigger & spaced */
.single-information.text-center .info-icon {
  font-size: 36px; 
  color: #666;
  margin-bottom: 10px;
}
.single-information h4 {
  margin: 0 0 5px 0;
  font-weight: 600;
}
.single-information p,
.single-information a {
  color: #555; 
  font-size: 0.95rem;
}

/* ========== Footer Area Improvements ========== */
.footer-area {
  background: #333;      /* Dark background for contrast */
  color: #fff;           /* Light text */
  padding: 20px 0;       /* Spacing */
  text-align: center;    /* Center content */
}

.footer-area .social-area {
  font-size: 0.95rem;    /* Slightly smaller text */
  line-height: 1.6;
  color: #fff;
}

.footer-area .social-area a {
  color: #fff;           /* Links are also white */
  text-decoration: none;
}
.footer-area .social-area a:hover {
  text-decoration: underline;
}

</style>

 <div class="information-area off-white ptb100">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4 col-sm-3 col-xs-12">
                                <div class="single-information text-center">
                                    <div class="info-icon">
                                        <i class="zmdi zmdi-phone"></i>
                                    </div>
                                    <h4>Phone</h4>
                                    <p><?php echo htmlentities($rows->PhoneNumber);?></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-3 col-xs-12">
                                <div class="single-information text-center">
                                    <div class="info-icon">
                                        <i class="zmdi zmdi-email-open"></i>
                                    </div>
                                    <h4>E-Mail</h4>
                                    <p><a href="mailto:company@email.com"><?php echo htmlentities($rows->EmailId);?></a></p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-3 col-xs-12">
                                <div class="single-information text-center">
                                    <div class="info-icon">
                                        <i class="zmdi zmdi-city-alt"></i>
                                    </div>
                                    <h4><?php echo htmlentities($rows->address);?></h4>
                                
                                </div>
                            </div>
                          
                        </div>
                    </div>
                </div>
            <!--information area are start-->

            <!--footer area are start-->
            <div class="footer-area" align="center">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 col-sm-6 col-xs-12">
                            <div class="social-area">
                             <?php echo htmlentities($rows->footercontent);?>
                            </div>
                        </div>
                      
                    </div>
                </div>
            </div>
            <?php }} ?>