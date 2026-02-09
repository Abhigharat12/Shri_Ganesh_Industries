<?php   

require_once 'core.php';

$sql = "SELECT * FROM lead";
$result = $connect->query($sql);

$output = array('data' => array());

if($result->num_rows > 0) { 

 // $row = $result->fetch_array();
 

 while($row = $result->fetch_array()) {
    $leadId = $row[0];
    // active 
    if($row[7] == 1) {
        

        $status = "<label class='label label-success'>New</label>";
    } else if($row[7] == 2){
        // deactivate member
        $status = "<label class='label label-success'>Working</label>";
    } // /else
    else if($row[7] == 3){
        // deactivate member
        $status = "<label class='label label-success'>Qualified</label>";
    }
    else if($row[7] == 4){
        // deactivate member
        $status = "<label class='label label-danger'>Failed</label>";
    }
    else if($row[7] == 5){
        // deactivate member
        $status = "<label class='label label-danger'>Closed</label>";
    }
    

    $button = '
    <div class="btn-group">
      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu">
        <li><a type="button" data-toggle="modal" data-target="#editBrandModel" onclick="editLead('.$leadId.')"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
        <li><a type="button" data-toggle="modal" data-target="#removeMemberModal" onclick="removeLead('.$leadId.')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>       
      </ul>
    </div>';

    $output['data'][] = array(      
        // lead name
       
        $row[1], 
        // phone
        $row[2],
        // email
        $row[3],            
        // city
       $row[4],
        // interest         
        $row[5],
        // source
        $row[6],
        // status
        $status,
        $button     
        );  
 } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
?>

