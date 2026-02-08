<?php //error_reporting(1); ?>
<?php include('./constant/layout/head.php');?>
<?php include('./constant/layout/header.php');?>

<?php include('./constant/layout/sidebar.php');?>   
<?php 


$lowStockSql = "SELECT * FROM product WHERE status = 1";
$lowStockQuery = $connect->query($lowStockSql);
$countLowStock = $lowStockQuery->num_rows;

$lowStockSql1 = "SELECT * FROM brands WHERE brand_status = 1";
$lowStockQuery1 = $connect->query($lowStockSql1);
$countLowStock1 = $lowStockQuery1->num_rows;

$date=date('Y-m-d');
    $lowStockSql3 = "SELECT * FROM product WHERE status = 1";
    //echo "SELECT * FROM product WHERE  expdate<='".$date."' AND status = 1" ;exit;
$lowStockQuery3 = $connect->query($lowStockSql3);
$countLowStock3 = $lowStockQuery3->num_rows;

$lowStockSql2 = "SELECT * FROM orders WHERE order_status = 1";
$lowStockQuery2= $connect->query($lowStockSql2);
$countLowStock2 = $lowStockQuery2->num_rows;

 $leadsql = "SELECT * FROM `lead` WHERE `lead_status` = '1'";
$leadquery = $connect->query($leadsql);
$countlead = $leadquery->num_rows;

$newleadsql = "SELECT * FROM `lead` WHERE `lead_status` = '1' and status=1";
$leadnewquery = $connect->query($newleadsql);
$countnewlead = $leadnewquery->num_rows;

$workleadsql = "SELECT * FROM `lead` WHERE `lead_status` = '1' and status=2";
$leadworkquery = $connect->query($workleadsql);
$countworklead = $leadworkquery->num_rows;

$contactleadsql = "SELECT * FROM `lead` WHERE `lead_status` = '1' and status=3";
$leadcontactquery = $connect->query($contactleadsql);
$countcontactlead = $leadcontactquery->num_rows;



$qualifiedleadsql = "SELECT * FROM `lead` WHERE `lead_status` = '1' and status=4";
$leadqualifiedquery = $connect->query($qualifiedleadsql);
$countqualifiedlead = $leadqualifiedquery->num_rows;

$failedleadsql = "SELECT * FROM `lead` WHERE `lead_status` = '1' and status=5";
$leadfailedquery = $connect->query($failedleadsql);
$countfailedlead = $leadfailedquery->num_rows;

$closedleadsql = "SELECT * FROM `lead` WHERE `lead_status` = '1' and status=6";
$leadclosedquery = $connect->query($closedleadsql);
$countclosedlead = $leadclosedquery->num_rows;

// High Priority Leads: leads with latest interaction having interest_probability 75% or 100%
$highPriorityStmt = $connect->prepare("SELECT COUNT(DISTINCT l.id) as count FROM lead l INNER JOIN lead_history lh ON l.id = lh.lead_id WHERE l.lead_status = 1 AND lh.interest_probability IN ('75%', '100%') AND lh.creation_date = (SELECT MAX(creation_date) FROM lead_history WHERE lead_id = l.id)");
$highPriorityStmt->execute();
$highPriorityResult = $highPriorityStmt->get_result();
$highPriorityRow = $highPriorityResult->fetch_assoc();
$counthighprioritylead = $highPriorityRow['count'] ?? 0;
$highPriorityStmt->close();

// Today’s Follow-ups: leads with latest interaction having follow_up_date = today and follow_up_status = 'Pending'
$todaysFollowupsStmt = $connect->prepare("SELECT COUNT(DISTINCT l.id) as count FROM lead l INNER JOIN lead_history lh ON l.id = lh.lead_id WHERE l.lead_status = 1 AND lh.follow_up_date = CURDATE() AND lh.follow_up_status = 'Pending' AND lh.creation_date = (SELECT MAX(creation_date) FROM lead_history WHERE lead_id = l.id)");
$todaysFollowupsStmt->execute();
$todaysFollowupsResult = $todaysFollowupsStmt->get_result();
$todaysFollowupsRow = $todaysFollowupsResult->fetch_assoc();
$counttodaysfollowups = $todaysFollowupsRow['count'] ?? 0;
$todaysFollowupsStmt->close();

// Conversion Rate: (Closed Leads / Total Leads) * 100
$conversionRate = ($countlead > 0) ? round(($countclosedlead / $countlead) * 100, 2) : 0;

//$connect->close();

?>
  
<style type="text/css">
    .ui-datepicker-calendar {
        display: none;
    }
</style>
      <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>  
        <div class="page-wrapper" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 100vh; padding: 20px 0;">
            
        <!--     <div class="row page-titles">
                <div class="col-md-12 align-self-center">
                    <div class="float-right"><h3 style="color:black;"><p style="color:black;"><?php echo date('l') .' '.date('d').'- '.date('m').'- '.date('Y'); ?></p></h3>
                    </div>
                    </div>
                
            </div> -->
            
            
            <div class="container-fluid ">
                
                 <div class="row justify-content-center">
       
                
                                 <?php if(isset($_SESSION['userId']) && $_SESSION['userId']==1) { ?>
                    <div class="col-md-3 col-sm-6 dashboard mb-4">
                        <div class="card dashboard-card shadow-lg border-0 rounded-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            <div class="card-body p-4">
                                <div class="media widget-ten align-items-center">
                                    <div class="media-left meida media-middle">
                                        <span class="icon-bg" style="background: rgba(255,255,255,0.2);"><i class="ti-receipt text-white"></i></span>
                                    </div>
                                    <div class="media-body media-text-right">
                                        <h2 class="text-white font-weight-bold"><?php echo $countlead; ?></h2>
                                         <a href="lead.php" class="text-decoration-none"><p class="m-b-0 text-white-50 font-weight-semibold">Total Leads</p></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                                       <?php }?>


                                        <?php if(isset($_SESSION['userId']) && $_SESSION['userId']==1) { ?>
                    <div class="col-md-3 col-sm-6 dashboard mb-4">
                        <div class="card dashboard-card shadow-lg border-0 rounded-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            <div class="card-body p-4">
                                <div class="media widget-ten align-items-center">
                                    <div class="media-left meida media-middle">
                                        <span class="icon-bg" style="background: rgba(255,255,255,0.2);"><i class="ti-new-window text-white"></i></span>
                                    </div>
                                    <div class="media-body media-text-right">
                                        <h2 class="text-white font-weight-bold"><?php echo $countnewlead; ?></h2>
                                         <a href="new-lead.php" class="text-decoration-none"><p class="m-b-0 text-white-50 font-weight-semibold">Total New Leads</p></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                                       <?php }?> <?php if(isset($_SESSION['userId']) && $_SESSION['userId']==1) { ?>
                    <div class="col-md-3 col-sm-6 dashboard mb-4">
                        <div class="card dashboard-card shadow-lg border-0 rounded-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            <div class="card-body p-4">
                                <div class="media widget-ten align-items-center">
                                    <div class="media-left meida media-middle">
                                        <span class="icon-bg" style="background: rgba(255,255,255,0.2);"><i class="ti-pencil-alt text-white"></i></span>
                                    </div>
                                    <div class="media-body media-text-right">
                                        <h2 class="text-white font-weight-bold"><?php echo $countworklead; ?></h2>
                                         <a href="working-lead.php" class="text-decoration-none"><p class="m-b-0 text-white-50 font-weight-semibold">Total Working Leads</p></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                                       <?php }?> <?php if(isset($_SESSION['userId']) && $_SESSION['userId']==1) { ?>
                    <div class="col-md-3 col-sm-6 dashboard mb-4">
                        <div class="card dashboard-card shadow-lg border-0 rounded-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            <div class="card-body p-4">
                                <div class="media widget-ten align-items-center">
                                    <div class="media-left meida media-middle">
                                        <span class="icon-bg" style="background: rgba(255,255,255,0.2);"><i class="ti-mobile text-white"></i></span>
                                    </div>
                                    <div class="media-body media-text-right">
                                        <h2 class="text-white font-weight-bold"><?php echo $countcontactlead; ?></h2>
                                         <a href="contact-lead.php" class="text-decoration-none"><p class="m-b-0 text-white-50 font-weight-semibold">Total Contacted Leads</p></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                                       <?php }?> <?php if(isset($_SESSION['userId']) && $_SESSION['userId']==1) { ?>
                    <div class="col-md-3 col-sm-6 dashboard mb-4">
                        <div class="card dashboard-card shadow-lg border-0 rounded-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            <div class="card-body p-4">
                                <div class="media widget-ten align-items-center">
                                    <div class="media-left meida media-middle">
                                        <span class="icon-bg" style="background: rgba(255,255,255,0.2);"><i class="ti-bookmark text-white"></i></span>
                                    </div>
                                    <div class="media-body media-text-right">
                                        <h2 class="text-white font-weight-bold"><?php echo $countqualifiedlead; ?></h2>
                                         <a href="qualified-lead.php" class="text-decoration-none"><p class="m-b-0 text-white-50 font-weight-semibold">Total Qualified Leads</p></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                                       <?php }?> <?php if(isset($_SESSION['userId']) && $_SESSION['userId']==1) { ?>
                    <div class="col-md-3 col-sm-6 dashboard mb-4">
                        <div class="card dashboard-card shadow-lg border-0 rounded-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            <div class="card-body p-4">
                                <div class="media widget-ten align-items-center">
                                    <div class="media-left meida media-middle">
                                        <span class="icon-bg" style="background: rgba(255,255,255,0.2);"><i class="ti-alert text-white"></i></span>
                                    </div>
                                    <div class="media-body media-text-right">
                                        <h2 class="text-white font-weight-bold"><?php echo $countfailedlead; ?></h2>
                                         <a href="failed-lead.php" class="text-decoration-none"><p class="m-b-0 text-white-50 font-weight-semibold">Total Failed Leads</p></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                                       <?php }?> <?php if(isset($_SESSION['userId']) && $_SESSION['userId']==1) { ?>
                    <div class="col-md-3 col-sm-6 dashboard mb-4">
                        <div class="card dashboard-card shadow-lg border-0 rounded-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            <div class="card-body p-4">
                                <div class="media widget-ten align-items-center">
                                    <div class="media-left meida media-middle">
                                        <span class="icon-bg" style="background: rgba(255,255,255,0.2);"><i class="ti-close text-white"></i></span>
                                    </div>
                                    <div class="media-body media-text-right">
                                        <h2 class="text-white font-weight-bold"><?php echo $countclosedlead; ?></h2>
                                         <a href="closed-lead.php" class="text-decoration-none"><p class="m-b-0 text-white-50 font-weight-semibold">Total Closed Leads</p></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                                       <?php }?>

                                 <?php if(isset($_SESSION['userId']) && $_SESSION['userId']==1) { ?>
                    <div class="col-md-3 col-sm-6 dashboard mb-4">
                        <div class="card dashboard-card shadow-lg border-0 rounded-lg" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: #fff; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            <div class="card-body p-4">
                                <div class="media widget-ten align-items-center">
                                    <div class="media-left meida media-middle">
                                        <span class="icon-bg" style="background: rgba(255,255,255,0.2);"><i class="ti-star text-white"></i></span>
                                    </div>
                                    <div class="media-body media-text-right">
                                        <h2 class="text-white font-weight-bold"><?php echo $counthighprioritylead; ?></h2>
                                         <a href="lead.php" class="text-decoration-none"><p class="m-b-0 text-white-50 font-weight-semibold">High Priority Leads</p></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                                       <?php }?> <?php if(isset($_SESSION['userId']) && $_SESSION['userId']==1) { ?>
                    <div class="col-md-3 col-sm-6 dashboard mb-4">
                        <div class="card dashboard-card shadow-lg border-0 rounded-lg" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: #fff; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            <div class="card-body p-4">
                                <div class="media widget-ten align-items-center">
                                    <div class="media-left meida media-middle">
                                        <span class="icon-bg" style="background: rgba(255,255,255,0.2);"><i class="ti-calendar text-white"></i></span>
                                    </div>
                                    <div class="media-body media-text-right">
                                        <h2 class="text-white font-weight-bold"><?php echo $counttodaysfollowups; ?></h2>
                                         <a href="lead.php" class="text-decoration-none"><p class="m-b-0 text-white-50 font-weight-semibold">Today’s Follow-ups</p></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                                       <?php }?> <?php if(isset($_SESSION['userId']) && $_SESSION['userId']==1) { ?>
                    <div class="col-md-3 col-sm-6 dashboard mb-4">
                        <div class="card dashboard-card shadow-lg border-0 rounded-lg" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: #fff; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            <div class="card-body p-4">
                                <div class="media widget-ten align-items-center">
                                    <div class="media-left meida media-middle">
                                        <span class="icon-bg" style="background: rgba(255,255,255,0.2);"><i class="ti-bar-chart text-white"></i></span>
                                    </div>
                                    <div class="media-body media-text-right">
                                        <h2 class="text-white font-weight-bold"><?php echo $conversionRate; ?>%</h2>
                                         <p class="m-b-0 text-white-50 font-weight-semibold">Conversion Rate</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                                       <?php }?> <?php if(isset($_SESSION['userId']) && $_SESSION['userId']==1) { ?>

                                 <?php }?>



     <div class="col-md-12">
<div class="card shadow-lg border-0 rounded-lg" style="background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); color: #343a40; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            <div class="card-header" style="background: linear-gradient(135deg, #7ebdf1 0%, #18a7ef 100%); color: #fff; border: none;">
                                <strong class="card-title">New Leads</strong>
                            </div>

                                <div class="card-body p-4">
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-hover table-striped">
                                        <thead style="color: blue;">
                                            <tr>
                                              <th>#</th>
                            <th><i class="ti-user"></i> Lead Name</th>
                            <th><i class="ti-mobile"></i> Phone</th>
                            <th><i class="ti-email"></i> Email</th>
                            <th><i class="ti-location-pin"></i> City</th>
                            <th><i class="ti-heart"></i> Interested In</th>
                            <th><i class="ti-share"></i> Source</th>
                          
                                                
                                            </tr>
                                       </thead>
                                       <tbody style="color: black;">
                                        <?php
                                        //include('./constant/connect');

 $sql = "SELECT * FROM `lead` WHERE `lead_status` = '1' and status=1";
 //echo $sql;exit;
$result=$connect->query($sql);
//print_r($result);exit;
$no = 1;
foreach ($result as $row) {

$no+=1;
    ?>
                                        <tr>
                                           <td class="text-center"><?php echo $no ?></td>

                                            <td class="text-center"><?php echo $row['lead_name'] ?></td>
                                            <td><?php echo $row['phone'] ?></td>

                                               <td><?php echo $row['email'] ?></td>
                                               <td><?php echo $row['city'] ?></td>

                                             <td><?php echo $row['interest'] ?></td>
                                              <td><?php echo $row['source'] ?></td>




                                        </tr>


                                    </tbody>
                                   <?php    
}

?>
                               </table>
                                </div>
                            </div>
                            
                    </div>
                </div>
                </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-lg border-0 rounded-lg" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border: none;">
                        <strong class="card-title">Lead Status Distribution</strong>
                    </div>
                    <div class="card-body p-4">
                        <div id="myChart" style="width:100%; max-width:600px; height:400px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-lg border-0 rounded-lg" style="background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border: none;">
                        <strong class="card-title">Product Rates Bar Chart</strong>
                    </div>
                    <div class="card-body p-4">
                        <div id="myChart1" style="width:100%; max-width:600px; height:400px;"></div>
                    </div>
                </div>
            </div>
        </div>


<?php
//error_reporting(0);
//require_once('../constant/connect.php');

// Lead status data for charts
$leadStatuses = [
    'New Leads' => $countnewlead,
    'Working Leads' => $countworklead,
    'Contacted Leads' => $countcontactlead,
    'Qualified Leads' => $countqualifiedlead,
    'Failed Leads' => $countfailedlead,
    'Closed Leads' => $countclosedlead
];

$datavalue1 = '';
foreach ($leadStatuses as $status => $count) {
    $datavalue1 .= "['" . $status . "'," . $count . "],";
}
$datavalue1 = rtrim($datavalue1, ','); // Remove trailing comma
?>


                
            </div>
        </div>
    </div>

            
            <?php include ('./constant/layout/footer.php');?>
        <script>
        $(function(){
            $(".preloader").fadeOut();
        })
        </script>
        <script>
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
var data = google.visualization.arrayToDataTable([ ['Lead Status', 'Count'],<?php echo $datavalue1;?>]);

var pieOptions = {
  title:'Lead Status Distribution',
  is3D:true,
  colors: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
};

var barOptions = {
  title:'Lead Status Comparison',
  colors: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
  legend: { position: 'none' }
};

var pieChart = new google.visualization.PieChart(document.getElementById('myChart'));
  pieChart.draw(data, pieOptions);

  var barChart = new google.visualization.BarChart(document.getElementById('myChart1'));
  barChart.draw(data, barOptions);
}
</script>
