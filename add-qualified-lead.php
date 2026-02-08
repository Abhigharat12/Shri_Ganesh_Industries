<?php include('./constant/layout/head.php');?>
<?php include('./constant/layout/header.php');?>

<?php include('./constant/layout/sidebar.php');?>   
 
        <div class="page-wrapper">
            
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Add Qualified Lead</h3> </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Add Qualified Lead</li>
                    </ol>
                </div>
            </div>
            
            
            <div class="container-fluid">
                
                
                
                
                <div class="row">
                    <div class="col-lg-12 mx-auto" >
                        <div class="card">
                            <div class="card-title">
                               
                            </div>
                            <div id="add-brand-messages"></div>
                            <div class="card-body">
                                <div class="input-states">
                                    <form class="row" method="POST"  id="submitBrandForm" action="php_action/createCategories.php" enctype="multipart/form-data">
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Lead Name</label>
                                            <input type="text"  class="form-control"id="categoriesName" placeholder="Lead Name" name="categoriesName"  required="" pattern="^[a-zA-z]+$"/>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class=" control-label">Phone</label>
                                            <input type="text"  class="form-control"id="categoriesName" placeholder="Phone" name="categoriesName"  required="" pattern="^[a-zA-z]+$"/>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Email</label>
                                            <input type="text"  class="form-control"id="categoriesName" placeholder="Phone" name="categoriesName"  required="" pattern="^[a-zA-z]+$"/>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">City</label>
                                            <input type="text"  class="form-control"id="categoriesName" placeholder="Phone" name="categoriesName"  required="" pattern="^[a-zA-z]+$"/>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Interested In</label>
                                            <input type="text"  class="form-control"id="categoriesName" placeholder="Phone" name="categoriesName"  required="" pattern="^[a-zA-z]+$"/>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class=" control-label">Source</label>
                                            <select class="form-control" id="categoriesStatus" name="categoriesStatus">
                                               <option value="">~~SELECT Source~~</option>
                                               <option value="1">Source1</option>
                                               <option value="2">Source2</option>
                                            </select>
                                        </div>
                                       <!--  <div class="form-group col-md-6">
                                            <label class=" control-label">Source</label>
                                            <select class="form-control" id="categoriesStatus" name="categoriesStatus">
                                                <option value="">~~SELECT Status~~</option>
                                                <option value="1">New </option>
                                                <option value="2">Working</option>
                                                <option value="2">Contacted</option>
                                                <option value="2">Qualified</option>
                                                <option value="2">Failed </option>
                                                <option value="2">Closed  </option>
                                            </select>
                                        </div> -->

                                        <div class="form-group col-md-12">
                                            <button type="submit" name="create" id="createCategoriesBtn" class="btn btn-primary btn-flat m-b-30 m-t-30">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                  
                </div>
                
               



<?php include('./constant/layout/footer.php');?>



