 <?php
 require_once(__DIR__ . '/../connect.php');

 ?>

 
        <div class="left-sidebar">
            
            <div class="scroll-sidebar">
                
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li class="nav-label">Home</li>
                        <li> <a href="../dashboard.php" aria-expanded="false"><i class="fa fa-tachometer"></i>Dashboard</a>
                        </li>


                        <li> <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-user"></i><span class="hide-menu">Lead</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="../add-lead.php">Add Lead</a></li>
                                <li><a href="../lead.php">Manage Lead</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-list"></i><span class="hide-menu">Lead Status</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="../new-lead.php">New </a></li>
                                <li><a href="../working-lead.php">Working</a></li>
                                <li><a href="../contact-lead.php">Contacted</a></li>
                                <li><a href="../qualified-lead.php">Qualified</a></li>
                                <li><a href="../failed-lead.php">Failed</a></li>
                                <li><a href="../closed-lead.php">Closed</a></li>
                            </ul>
                        </li>
                       
                        

    
                    </ul>   
                </nav>
                
            </div>
            
        </div>
        
