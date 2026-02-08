<?php

include(__DIR__ . '/../check.php');
require_once(__DIR__ . '/../connect.php');
?>
    
    <div id="main-wrapper">
        
        <div class="header">
            <marquee class="d-lg-none d-block">
                <div class="ml-lg-5 pl-lg-5 ">
                  
                     <b id="ti" class="ml-lg-5 pl-lg-5"></b>
                   
                   
                    </div>
            </marquee>
            <nav class="navbar top-navbar navbar-expand-md navbar-light" style="min-height: 60px; padding: 0 15px;">

                <div class="navbar-header">
                    <a class="navbar-brand" href="index.php" style="display: flex; align-items: center; justify-content: center; width: 100%;">
                        <img src="../assets/uploadImage/Logo/logo.png" alt="homepage" class="dark-logo" style="max-width:120px; height:auto; padding:3px; border-radius:5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border: 1px solid rgba(255,255,255,0.2);"/>
                    </a>
                </div>
                
                <div class="navbar-collapse">
                    
                    <ul class="navbar-nav  mt-md-0">
                        
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-white  " href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted  " href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        
                      
                        
                    </ul>
                         <marquee behavior="scroll" direction="left" scrollamount="1"><p style="color: white;">
                <!-- This Project is developed for Academic study purpose only. | Never Sell or Distribute with your Name OR Branding</p> -->
            </marquee>
                   <!--  <div class="ml-lg-5 pl-lg-5 d-lg-block d-none">
                     <b id="time" class="ml-lg-5 pl-lg-5"></b>
                    </div> -->
                    <ul class="navbar-nav my-lg-0 ml-auto">

                      
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                
                                <img src="./assets/uploadImage/Profile/young-woman-avatar-facial-features-stylish-userpic-flat-cartoon-design-elegant-lady-blue-jacket-close-up-portrait-80474088.jpg" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                                <ul class="dropdown-user">
                                   <?php if(isset($_SESSION['userId']) && $_SESSION['userId']==1) { ?>
                                     <li><a href="app/changepassword.php"><i class="fa fa-key"></i> Change Password</a></li>
                                      <li><a href="#"><i class="fa fa-user"></i> Add user</a></li>
                                     <?php }?>
                               
                                    <li><a href="../logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

        </div>
