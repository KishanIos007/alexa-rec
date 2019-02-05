<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Author Management 
        <small>Add / Edit Author</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Add Author</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <?php echo form_open_multipart('author/addNew', array('id' => 'authorForm'));?>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Author Name</label>
                                            <input type="text" class="form-control required" value="<?php echo set_value('name'); ?>" id="authorname" name="name" >
                                    </div>
									
                                </div>
								<div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Author Nick Name</label>
                                         <input type="text" class="form-control required" value="<?php echo set_value('nick_name'); ?>" id="authornickname" name="nick_name" >
                                    </div>
                                </div>
											<?php
						if($role == ROLE_ADMIN)
						{ ?>
								<div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">User</label>
							
                                        <select name="user_id" id="user_id" class="form-control" >
                                            <option value="">select User</option>
                                            <?php foreach ($users as $user) { ?>
                                                <option value="<?php echo $user->userId ?>">
                                                    <?php echo $user->name; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
										
                                    </div>
									
                                </div>
								 <?php } else { ?>
										 
										  <?php } ?>
								<div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select name="gender" id="gender" class="form-control required" required="required">
                                            <option value="">select gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            
                                        </select>
                                    </div>
									
                                </div>
                            </div>
                        </div><!-- /.box-body -->
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
                <?php } ?>
                <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>    
    </section>
    
</div>
<script>

$('select#title_id').on('change', function() {
  var notval = this.value;
  if(notval == 'NotIn')
  {
	  $('#newtitle').show();
	   $("#newtitle_id").attr("required","required");
	  
  }
  else
  {
	  $('#newtitle').hide();
	   $('#newtitle_id').val('');
	    $('#newtitle_id').removeAttr('required');
  }  
  
});
$(document).ready(function(){
	

			
			$('#myForm input').on('change', function() {
   check_radio = $('input[name=group1]:checked', '#myForm').val();
   if(check_radio == 1)
   {
	   $('#author_block').hide();
	   $('#author_id').removeAttr('required');
	   
   }
   if(check_radio == 0)
   {
	   $('#author_block').show();
	    
		$("#author_id").attr("required","required");
   }
});
			
});
</script>