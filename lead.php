
<?php
//require_once './php_action/core.php';
 include('./constant/layout/head.php');?>
<?php include('./constant/layout/header.php');?>

<?php include('./constant/layout/sidebar.php');?>   
<?php include('./constant/connect.php');?>

       <div class="page-wrapper">
            
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary"> View Lead</h3> </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">View Lead</li>
                    </ol>
                </div>
            </div>
            
            
            <div class="container-fluid">
                
                
                
                
                 <div class="card">
                            <div class="card-body">
                              
                            <a href="add-lead.php"><button class="btn btn-primary">Add Lead</button></a>
                         
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                             
                            <th>#</th>
                            <th>Lead Name</th>
                            <th>Phone</th>                           
                            <th>Email </th>
                            <th>City</th>
                            <th>Interested In</th>
                            <th>Source</th>
                            
                            <th>Status</th>
                            <th>Action</th>
                                            </tr>
                                       </thead>
                                       <tbody>
                                        <?php
                                        
    
   $sql = "SELECT * FROM `lead` WHERE `lead_status` = '1'";
  $result1 = $connect->query($sql);
  $i=1;
   while($row = $result1->fetch_array()) {
                                    ?>    
                                        <tr>
                                                
                                            <td class="text-center"><?php echo $i ?></td>
                                                
                                            <td class="text-center"><?php echo $row['lead_name'] ?></td>
                                            <td><?php echo $row['phone'] ?></td>
                                           
                                               <td><?php echo $row['email'] ?></td> 
                                               <td><?php echo $row['city'] ?></td> 
                                    
                                             <td><?php echo $row['interest'] ?></td>
                                              <td><?php echo $row['source'] ?></td>
                                              
                                           
                                             <td><?php  if($row['status']==1)
                                            {
                                                 
                                                 $status = "<label class='label label-primary' ><h4>New</h4></label>";
                                                 echo $status;
                                            }
                                            else if($row['status'] == 2){
                                                $status = "<label class='label label-primary'><h4> Working</h4></label>";
                                                echo $status;
                                            }else if($row['status'] == 3){
                                                $status = "<label class='label label-primary'><h4> Contacted</h4></label>";
                                                echo $status;
                                            }else if($row['status'] == 4){
                                                $status = "<label class='label label-success'><h4> Qualified</h4></label>";
                                                echo $status;
                                            }else if($row['status'] == 5){
                                                $status = "<label class='label label-danger'><h4> Failed</h4></label>";
                                                echo $status;
                                            }
                                            else if($row['status'] == 6){
                                                $status = "<label class='label label-danger'><h4> Closed</h4></label>";
                                                echo $status;
                                            }
                                        ?></td>
                                           
                                            <td>
            
                                              <a href="editlead.php?id=<?php echo $row['id']?>"><button type="button" class="btn btn-xs btn-primary" ><i class="fa fa-pencil"></i></button></a> 
                                              

             
                                                <a href="php_action/removeLead.php?id=<?php echo $row['id']?>" ><button type="button" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure to delete this record?')"><i class="fa fa-trash"></i></button></a>
                                           
                                                
                                                </td>
                                        </tr>
                                      <?php $i++;}  ?>
                                    </tbody>
                        
                               </table>
                                </div>
                            </div>
                        </div>

                        <!-- High Priority Leads Section -->
                        <div class="card mt-4" style="border: 2px solid #ffc107;">
                            <div class="card-body">
                                <h3 class="text-warning"><i class="fa fa-star"></i> High Priority Leads</h3>
                                <small class="text-muted">Leads with 75% or 100% interest probability requiring immediate attention</small>

                                <?php
                                $statuses = [
                                    1 => 'New',
                                    2 => 'Working',
                                    3 => 'Contacted'
                                ];

                                foreach ($statuses as $statusId => $statusName) {
                                    echo "<h4 class='text-primary mt-4'>$statusName High Priority Leads</h4>";
                                    $sql = "SELECT l.id, l.lead_name, l.phone, l.interest, lh.interest_probability, lh.interaction_notes, lh.next_step
                                            FROM lead l
                                            INNER JOIN lead_history lh ON l.id = lh.lead_id
                                            WHERE l.lead_status = 1 AND l.status = ? AND lh.interest_probability IN ('75%', '100%')
                                            AND lh.creation_date = (
                                                SELECT MAX(creation_date)
                                                FROM lead_history
                                                WHERE lead_id = l.id AND interest_probability IN ('75%', '100%')
                                            )
                                            ORDER BY l.id";
                                    $stmt = $connect->prepare($sql);
                                    $stmt->bind_param("i", $statusId);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result->num_rows > 0) {
                                        echo '<div class="table-responsive m-t-20">';
                                        echo '<table class="table table-bordered table-striped table-sm">';
                                        echo '<thead>';
                                        echo '<tr>';
                                        echo '<th>Lead Name</th>';
                                        echo '<th>Interested In</th>';
                                        echo '<th>Interest Probability</th>';
                                        echo '<th>Latest Interaction Notes</th>';
                                        echo '<th>Next Step</th>';
                                        echo '<th>Contact Number</th>';
                                        echo '<th>Actions</th>';
                                        echo '</tr>';
                                        echo '</thead>';
                                        echo '<tbody>';
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<tr>';
                                            echo '<td>' . htmlspecialchars($row['lead_name']) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['interest']) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['interest_probability']) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['interaction_notes'] ?? 'N/A') . '</td>';
                                            echo '<td>' . htmlspecialchars($row['next_step'] ?? 'N/A') . '</td>';
                                            echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
                                            echo '<td>';
                                            echo '<a href="editlead.php?id=' . $row['id'] . '"><button type="button" class="btn btn-success btn-sm">Mark as Done</button></a>';
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                        echo '</tbody>';
                                        echo '</table>';
                                        echo '</div>';
                                    } else {
                                        echo '<p class="text-muted">No high priority leads in this category.</p>';
                                    }
                                    $stmt->close();
                                }
                                ?>
                            </div>
                        </div>

                        <!-- Follow-up Reminder Section -->
                        <div class="card mt-4">
                            <div class="card-body">
                                <h3 class="text-primary"><i class="fa fa-calendar-check-o"></i> Follow-up Reminders</h3>
                                <small class="text-muted">Manage today's and overdue follow-ups for active leads</small>

                                <!-- Today’s Follow-ups -->
                                <div class="mt-4">
                                    <h4 class="text-success">Today’s Follow-ups</h4>
                                    <?php
                                    $todaySql = "SELECT l.id, l.lead_name, l.status, l.phone, lh.interest_probability, lh.follow_up_date, lh.interaction_notes, lh.next_step
                                                 FROM lead l
                                                 INNER JOIN lead_history lh ON l.id = lh.lead_id
                                                 WHERE l.lead_status = 1 AND lh.follow_up_date = CURDATE()
                                                 AND lh.creation_date = (
                                                     SELECT MAX(creation_date)
                                                     FROM lead_history
                                                     WHERE lead_id = l.id
                                                 )
                                                 ORDER BY l.id";
                                    $todayStmt = $connect->prepare($todaySql);
                                    $todayStmt->execute();
                                    $todayResult = $todayStmt->get_result();

                                    if ($todayResult->num_rows > 0) {
                                        echo '<div class="table-responsive m-t-20">';
                                        echo '<table class="table table-bordered table-striped table-sm">';
                                        echo '<thead>';
                                        echo '<tr>';
                                        echo '<th>Lead Name</th>';
                                        echo '<th>Lead Status</th>';
                                        echo '<th>Contact Number</th>';
                                        echo '<th>Interest Probability</th>';
                                        echo '<th>Follow-up Date</th>';
                                        echo '<th>Latest Interaction Notes</th>';
                                        echo '<th>Next Step</th>';
                                        echo '<th>Actions</th>';
                                        echo '</tr>';
                                        echo '</thead>';
                                        echo '<tbody>';
                                        while ($row = $todayResult->fetch_assoc()) {
                                            $statusText = '';
                                            switch ($row['status']) {
                                                case 1: $statusText = 'New'; break;
                                                case 2: $statusText = 'Working'; break;
                                                case 3: $statusText = 'Contacted'; break;
                                                case 4: $statusText = 'Qualified'; break;
                                                case 5: $statusText = 'Failed'; break;
                                                case 6: $statusText = 'Closed'; break;
                                            }
                                            echo '<tr>';
                                            echo '<td>' . htmlspecialchars($row['lead_name']) . '</td>';
                                            echo '<td>' . htmlspecialchars($statusText) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['interest_probability']) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['follow_up_date']) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['interaction_notes'] ?? 'N/A') . '</td>';
                                            echo '<td>' . htmlspecialchars($row['next_step'] ?? 'N/A') . '</td>';
                                            echo '<td>';
                                            echo '<a href="editlead.php?id=' . $row['id'] . '"><button type="button" class="btn btn-primary btn-sm">Update Follow-up</button></a>';
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                        echo '</tbody>';
                                        echo '</table>';
                                        echo '</div>';
                                    } else {
                                        echo '<p class="text-muted">No follow-ups scheduled for today.</p>';
                                    }
                                    $todayStmt->close();
                                    ?>
                                </div>

                                <!-- Overdue Follow-ups -->
                                <div class="mt-4">
                                    <h4 class="text-danger">Overdue Follow-ups</h4>
                                    <?php
                                    $overdueSql = "SELECT l.id, l.lead_name, l.status, l.phone, lh.interest_probability, lh.follow_up_date, lh.interaction_notes, lh.next_step
                                                   FROM lead l
                                                   INNER JOIN lead_history lh ON l.id = lh.lead_id
                                                   WHERE l.lead_status = 1 AND lh.follow_up_date < CURDATE() AND lh.follow_up_status = 'Pending'
                                                   AND lh.creation_date = (
                                                       SELECT MAX(creation_date)
                                                       FROM lead_history
                                                       WHERE lead_id = l.id
                                                   )
                                                   ORDER BY l.id";
                                    $overdueStmt = $connect->prepare($overdueSql);
                                    $overdueStmt->execute();
                                    $overdueResult = $overdueStmt->get_result();

                                    if ($overdueResult->num_rows > 0) {
                                        echo '<div class="table-responsive m-t-20">';
                                        echo '<table class="table table-bordered table-striped table-sm">';
                                        echo '<thead>';
                                        echo '<tr>';
                                        echo '<th>Lead Name</th>';
                                        echo '<th>Lead Status</th>';
                                        echo '<th>Contact Number</th>';
                                        echo '<th>Interest Probability</th>';
                                        echo '<th>Follow-up Date</th>';
                                        echo '<th>Latest Interaction Notes</th>';
                                        echo '<th>Next Step</th>';
                                        echo '<th>Actions</th>';
                                        echo '</tr>';
                                        echo '</thead>';
                                        echo '<tbody>';
                                        while ($row = $overdueResult->fetch_assoc()) {
                                            $statusText = '';
                                            switch ($row['status']) {
                                                case 1: $statusText = 'New'; break;
                                                case 2: $statusText = 'Working'; break;
                                                case 3: $statusText = 'Contacted'; break;
                                                case 4: $statusText = 'Qualified'; break;
                                                case 5: $statusText = 'Failed'; break;
                                                case 6: $statusText = 'Closed'; break;
                                            }
                                            echo '<tr class="table-danger">';
                                            echo '<td>' . htmlspecialchars($row['lead_name']) . '</td>';
                                            echo '<td>' . htmlspecialchars($statusText) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['interest_probability']) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['follow_up_date']) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['interaction_notes'] ?? 'N/A') . '</td>';
                                            echo '<td>' . htmlspecialchars($row['next_step'] ?? 'N/A') . '</td>';
                                            echo '<td>';
                                            echo '<a href="editlead.php?id=' . $row['id'] . '"><button type="button" class="btn btn-primary btn-sm">Update Follow-up</button></a>';
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                        echo '</tbody>';
                                        echo '</table>';
                                        echo '</div>';
                                    } else {
                                        echo '<p class="text-muted">No overdue follow-ups.</p>';
                                    }
                                    $overdueStmt->close();
                                    ?>
                                </div>
                            </div>
                        </div>
</div></div>

<?php include('./constant/layout/footer.php');?>



