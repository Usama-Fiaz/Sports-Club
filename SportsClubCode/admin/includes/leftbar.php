  
           
           <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="dashboard.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-files-o"></i> Category<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="add-category.php">Add</a>
                                </li>
                                <li>
                                    <a href="manage-category.php">Manage</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-files-o"></i>Shopping-Category<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="add-shopping-category.php">Add</a>
                                </li>
                                <li>
                                    <a href="manage-shoppingcategory.php">Manage</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-files-o"></i>Sub-Category<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="add-subcategory.php">Add</a>
                                </li>
                                <li>
                                    <a href="manage-sub-category.php">Manage</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
   <li>    
                        <li>
                            <a href="#"><i class="fa fa-files-o"></i>Products<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="add-products.php">Add</a>
                                </li>
                                <li>
                                    <a href="manage-products.php">Manage</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                <a href="#"><i class="fa fa-shopping-cart"></i> Orders <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="all-orders.php">All Orders <span class="badge"><?php echo $torders;?></span></a></li>
                    <li><a href="new-order.php">New Orders <span class="badge"><?php echo $norders;?></span></a></li>
                    <li><a href="packed-orders.php">Packed Orders <span class="badge"><?php echo $porders;?></span></a></li>
                    <li><a href="dispatched-orders.php">Dispatched Orders <span class="badge"><?php echo $dtorders;?></span></a></li>
                    <li><a href="intransit-orders.php">In Transit Orders <span class="badge"><?php echo $intorders;?></span></a></li>
                    <li><a href="outfordelivery-orders.php">Out for Delivery Orders <span class="badge"><?php echo $otforders;?></span></a></li>
                    <li><a href="delivered-orders.php">Delivered Orders <span class="badge"><?php echo $deliveredorders;?></span></a></li>
                    <li><a href="cancelled-orders.php">Cancelled Orders <span class="badge"><?php echo $cancelledorders;?></span></a></li>
                </ul>
            </li>

   <li>
                            <a href="manage-sponsers.php"><i class="fa fa-files-o"></i> Manage Sponsers</a>
                        </li>

    <li>
                            <a href="#"><i class="fa fa-table fa-fw"></i> Events<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="add-event.php">Add</a>
                                </li>
                                <li>
                                    <a href="manage-events.php">Manage</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>

    <li>
                            <a href="manage-users.php"><i class="fa fa-users"></i> Manage Users</a>
                        </li>
                       <li>
                        <a href="#"><i class="fa fa-table fa-fw"></i> Manage Clubs<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="add-club.php">Add</a>
                                </li>
                                <li>
                                    <a href="manage-clubs.php">Manage</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>

                        <li>
                        <a href="#"><i class="fa fa-files-o fa-fw"></i> Trainers<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="add-trainer.php">Add </a>
                                </li>
                                <li>
                                    <a href="manage-trainer.php">Manage </a>
                                </li>
                            
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                        <a href="#"><i class="fa fa-files-o fa-fw"></i> Training Sessions<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="add-training.php">Add </a>
                                </li>
                                <li>
                                    <a href="manage-training.php">Manage </a>
                                </li>
                            
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
 <li>   <a href="#"><i class="fa fa-files-o fa-fw"></i> Training Category<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="add-trainingcategory.php">Add </a>
                                </li>
                                <li>
                                    <a href="manage-trainingcategory.php">Manage </a>
                                </li>
                            
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
 <li>

                            <a href="subscribers.php"><i class="fa fa-users"></i> Manage Subscribers</a>
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-book"></i> Manage Events Bookings<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="all-booking.php">All Bookings</a>
                                </li>
                                <li>
                                    <a href="new-bookings.php">New Bookings</a>
                                </li>
                                <li>
                                    <a href="cancelled-booking.php">Cancelled Bookings</a>
                                </li>
                                <li>
                                    <a href="confirmed-bookings.php">Confirmed Bookings</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-book"></i> Manage Training Bookings<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="all-trainingbooking.php">All Bookings</a>
                                </li>
                                <li>
                                    <a href="new-trainingbooking.php">New Bookings</a>
                                </li>
                                <li>
                                    <a href="cancelled-trainingbooking.php">Cancelled Bookings</a>
                                </li>
                                <li>
                                    <a href="confirmedtrainingbooking.php">Confirmed Bookings</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
   <li>
                            <a href="#"><i class="fa fa-files-o fa-fw"></i> News<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="add-news.php">Add </a>
                                </li>
                                <li>
                                    <a href="manage-news.php">Manage </a>
                                </li>
                            
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> Website Setting<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="about-us.php">About us </a>
                                </li>
                                <li>
                                    <a href="website-setting.php">General Settings</a>
                                </li>
                            
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>