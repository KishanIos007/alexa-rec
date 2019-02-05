<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Story Management
        <small>Add / Edit Story</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Add Story</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <?php echo form_open_multipart('story/addNew', array('id' => 'myForm'));?>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Title</label>
                                        <select name="title_id" id="title_id" class="form-control required" required="required">
                                            <option value="">select Title</option>
											<option value="NotIn">None of this</option>
                                            <?php foreach ($categories as $cat) { ?>
                                                <option value="<?php echo $cat->id ?>">
                                                    <?php echo $cat->name; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Audio File</label>
                                        <input name="file" type="file" required="required" class="form-control required">
                                    </div>    
                                </div>
								 <div class="col-md-6" id='newtitle' style='display:none'>                                
                                    <div class="form-group">
                                        <label for="newtitle">Add New Title</label>
                                        <input name="newtitle" id='newtitle_id' type="text"  class="form-control required">
                                    </div>    
                                </div>
								<div class="col-md-6"  >                                
                                    <div class="form-group">
									  <?php
						if($role == ROLE_ADMIN)
						{ ?>
									<label for="forauthor">Select author</label>
								
					<?php
						}
						else
						{
						?>
													<label for="forauthor">Are you author</label>
													<input type="radio" name="group1" value="1" isChecked="false" class="author_id" >Yes
													<input type="radio" name="group1" value="0" checked class="author_id" >No
						<?php } ?> 
						<div id="author_block" style="display:block">
							<select name="author_id" id="author_id" class="form-control required" required="required">
									<option value="">select Author</option>
									<?php foreach ($authors as $author) { ?>
									  <option value="<?php echo $author->authorid ?>">
                                                    <?php echo $author->name; ?>
                                                </option>
                                            <?php } ?>
							</select>
						</div>	
						
			</div>    
                                </div>
								  <div class="col-md-6">        
							  
                                    <div class="form-group">
                                        <label for="fname">Kind</label>
										  <select name="kind_id" id="kind_id" class="form-control required" >
                                            <option value="">select kind</option>
                                            <?php foreach ($kinds as $kind) { ?>
                                                <option value="<?php echo $kind->kindid ?>">
                                                    <?php echo $kind->kind_name; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                     </div>
                                </div>
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="fname">Description</label>
                                        <textarea name="description" class="form-control required" required="required"><?php echo set_value('name'); ?></textarea>
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