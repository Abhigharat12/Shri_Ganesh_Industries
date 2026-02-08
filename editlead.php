<?php include('./constant/layout/head.php');?>
<?php include('./constant/layout/header.php');?>

<?php include('./constant/layout/sidebar.php');?>

<?php include('./constant/connect.php');

$sql="SELECT * from `lead` where  id='".$_GET['id']."'";
  $result=$connect->query($sql)->fetch_assoc();  ?>    
 
        <div class="page-wrapper">
            
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Edit Lead Management</h3> </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Edit Lead</li>
                    </ol>
                </div>
            </div>


            <div class="container-fluid">
                <?php if(isset($_SESSION['success'])) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>
                <?php if(isset($_SESSION['error'])) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>

                <!-- Lead Details Section -->
                <div class="row">
                    <div class="col-lg-10" style="margin-left: 5%;">
                        <div class="card">
                            <div class="card-title">
                                <h4 class="text-primary"><i class="fa fa-user"></i> Lead Information & Basic Details</h4>
                                <small class="text-muted">Primary lead details that define the lead profile</small>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="php_action/editLead.php?id=<?php echo $_GET['id']; ?>">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Lead Name</label>
                                            <input type="text" class="form-control" name="lead" value="<?php echo htmlspecialchars($result['lead_name']); ?>" required pattern="^[a-zA-Z\s]+$"/>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Contact Person Name</label>
                                            <input type="text" class="form-control" name="contact_person" value="<?php echo htmlspecialchars($result['contact_person'] ?? ''); ?>" />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Phone</label>
                                            <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($result['phone']); ?>" required pattern="^[0-9]+$"/>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Email</label>
                                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($result['email']); ?>" required />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">City</label>
                                            <input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars($result['city']); ?>" required />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Interested In</label>
                                            <input type="text" class="form-control" name="interest" value="<?php echo htmlspecialchars($result['interest']); ?>" required />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Source</label>
                                            <select class="form-control" name="source">
                                                <option value="Call" <?php if($result['source'] == "Call") echo "selected"; ?>>Call</option>
                                                <option value="Organic" <?php if($result['source'] == "Organic") echo "selected"; ?>>Organic</option>
                                                <option value="SocialMedia" <?php if($result['source'] == "SocialMedia") echo "selected"; ?>>Social Media</option>
                                                <option value="Website" <?php if($result['source'] == "Website") echo "selected"; ?>>Website</option>
                                                <option value="Campaign" <?php if($result['source'] == "Campaign") echo "selected"; ?>>Campaign</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Status</label>
                                            <select class="form-control" name="status">
                                                <option value="1" <?php if($result['status'] == 1) echo "selected"; ?>>New</option>
                                                <option value="2" <?php if($result['status'] == 2) echo "selected"; ?>>Working</option>
                                                <option value="3" <?php if($result['status'] == 3) echo "selected"; ?>>Contacted</option>
                                                <option value="4" <?php if($result['status'] == 4) echo "selected"; ?>>Qualified</option>
                                                <option value="5" <?php if($result['status'] == 5) echo "selected"; ?>>Failed</option>
                                                <option value="6" <?php if($result['status'] == 6) echo "selected"; ?>>Closed</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <button type="submit" name="create" class="btn btn-primary">Update Lead</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lead History Section -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-title">
                                <h4 class="text-primary"><i class="fa fa-history"></i> Lead Interaction History & Follow-Ups</h4>
                                <small class="text-muted">Complete record of enquiries, interactions, follow-ups, and progress updates</small>
                            </div>
                            <div class="card-body">
                                <button type="button" class="btn btn-success mb-3" id="addEnquiredStatus">Add Enquired Status</button>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-sm" id="leadHistoryTable">
                                        <thead>
                                            <tr>
                                                <th>Creation Date</th>
                                                <th>Last Interaction Date</th>
                                                <th>Interaction Type</th>
                                                <th>Interest Probability</th>
                                                <th>Interaction Notes</th>
                                                <th>Follow-up Date</th>
                                                <th>Follow-up Status</th>
                                                <th>Next Step</th>
                                                <th>Updated By</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $historySql = "SELECT lh.id, lh.creation_date, lh.last_interaction, lh.interaction_type, lh.interaction_notes, lh.interest_probability, lh.follow_up_date, lh.follow_up_status, lh.next_step, u.username AS updated_by_name FROM lead_history lh LEFT JOIN users u ON lh.updated_by = u.user_id WHERE lh.lead_id = ? ORDER BY lh.creation_date DESC";
                                            $stmt = $connect->prepare($historySql);
                                            $stmt->bind_param("i", $_GET['id']);
                                            $stmt->execute();
                                            $historyResult = $stmt->get_result();
                                            while ($history = $historyResult->fetch_assoc()) {
                                                echo "<tr data-id='" . $history['id'] . "'>";
                                                echo "<td>" . htmlspecialchars(date('Y-m-d H:i:s', strtotime($history['creation_date']))) . "</td>";
                                                echo "<td>" . htmlspecialchars($history['last_interaction'] ? date('Y-m-d H:i:s', strtotime($history['last_interaction'])) : 'N/A') . "</td>";
                                                echo "<td><select class='form-control interaction-type'>";
                                                foreach (['Call', 'WhatsApp', 'Email', 'Meeting', 'Visit'] as $type) {
                                                    $selected = ($history['interaction_type'] == $type) ? 'selected' : '';
                                                    echo "<option value='$type' $selected>$type</option>";
                                                }
                                                echo "</select></td>";
                                                echo "<td><select class='form-control interest-probability'>";
                                                foreach (['25%', '50%', '75%', '100%'] as $prob) {
                                                    $selected = ($history['interest_probability'] == $prob) ? 'selected' : '';
                                                    echo "<option value='$prob' $selected>$prob</option>";
                                                }
                                                echo "</select></td>";
                                                echo "<td><textarea class='form-control interaction-notes' rows='2'>" . htmlspecialchars($history['interaction_notes'] ?? '') . "</textarea></td>";
                                                echo "<td><input type='date' class='form-control follow-up-date' value='" . htmlspecialchars($history['follow_up_date'] ?? '') . "' /></td>";
                                                echo "<td><select class='form-control follow-up-status'>";
                                                foreach (['Pending', 'Done', 'Missed', 'Rescheduled'] as $status) {
                                                    $selected = ($history['follow_up_status'] == $status) ? 'selected' : '';
                                                    echo "<option value='$status' $selected>$status</option>";
                                                }
                                                echo "</select></td>";
                                                echo "<td><input type='text' class='form-control next-step' value='" . htmlspecialchars($history['next_step'] ?? '') . "' /></td>";
                                                echo "<td>" . htmlspecialchars($history['updated_by_name'] ?? 'N/A') . "</td>";
                                                echo "<td>";
                                                echo "<button class='btn btn-primary btn-sm edit-history' data-id='" . $history['id'] . "'>Save</button> ";
                                                echo "<button class='btn btn-danger btn-sm delete-history' data-id='" . $history['id'] . "' data-lead-id='" . $_GET['id'] . "'>Delete</button>";
                                                echo "</td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete this history record? This action cannot be undone.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>



<script src="assets/js/lib/jquery/jquery.min.js"></script>
<script>
$(document).ready(function() {
    // Add Enquired Status button
    $('#addEnquiredStatus').click(function() {
        toastr.info('Adding new history record...', 'Please wait');
        $.post('php_action/addLeadHistory.php', { lead_id: <?php echo $_GET['id']; ?> }, function(response) {
            try {
                var res = JSON.parse(response);
                if (res.success) {
                    toastr.success('Enquired status added successfully.', 'Success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error(res.message, 'Error');
                }
            } catch (e) {
                toastr.error('An unexpected error occurred.', 'Error');
            }
        }).fail(function(xhr, status, error) {
            toastr.error('Failed to add history record. Please try again.', 'Error');
        });
    });

    // Edit history button
    $('.edit-history').click(function() {
        var row = $(this).closest('tr');
        var historyId = $(this).data('id');
        var interactionType = row.find('.interaction-type').val();
        var interestProbability = row.find('.interest-probability').val();
        var interactionNotes = row.find('.interaction-notes').val();
        var followUpDate = row.find('.follow-up-date').val();
        var followUpStatus = row.find('.follow-up-status').val();
        var nextStep = row.find('.next-step').val();

        toastr.info('Updating history record...', 'Please wait');
        $.post('php_action/updateLeadHistory.php', {
            history_id: historyId,
            interaction_type: interactionType,
            interest_probability: interestProbability,
            interaction_notes: interactionNotes,
            follow_up_date: followUpDate,
            follow_up_status: followUpStatus,
            next_step: nextStep
        }, function(response) {
            var res = JSON.parse(response);
            if (res.success) {
                toastr.success('Lead history updated successfully.', 'Success');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                toastr.error('Failed to update history record.', 'Error');
            }
        }).fail(function(xhr, status, error) {
            toastr.error('Failed to update history record. Please try again.', 'Error');
        });
    });

    // Delete history button
    var deleteHistoryId, deleteLeadId;
    $('.delete-history').click(function() {
        deleteHistoryId = $(this).data('id');
        deleteLeadId = $(this).data('lead-id');
        $('#deleteModal').modal('show');
    });

    // Confirm delete
    $('#confirmDelete').click(function() {
        $('#deleteModal').modal('hide');
        toastr.info('Deleting history record...', 'Please wait');
        $.post('php_action/deleteLeadHistory.php', { history_id: deleteHistoryId, lead_id: deleteLeadId }, function(response) {
            var res = JSON.parse(response);
            if (res.success) {
                toastr.success('Record deleted successfully.', 'Success');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                toastr.error(res.message, 'Error');
            }
        }).fail(function(xhr, status, error) {
            toastr.error('Failed to delete record. Please try again.', 'Error');
        });
    });
});
</script>

<?php include('./constant/layout/footer.php');?>



