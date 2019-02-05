<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Author Management
        <small>Add</small>
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>author/addNew"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Author List</h3>
                    <div class="box-tools">
                        <form action="<?php echo base_url() ?>story" method="POST" id="searchList">
                           <!-- <div class="input-group">
                              <input type="text" name="searchText" value="<?php //echo $searchText; ?>" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search"/>
                              <div class="input-group-btn">
                                <button class="btn btn-sm btn-default searchList"><i class="fa fa-search"></i></button>
                              </div>
                            </div>-->
                        </form>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                        
                        <th>Author Name</th>
						<th>Author Nick Name</th>
						 <?php if($role == ROLE_ADMIN){ ?>
                        <th>User Name</th>
						<?php } ?>
                        <th>Gender</th>
                        
                        <?php if($role == ROLE_ADMIN){ ?>
                            <!--<th>Actions</th> -->
                        <?php } ?>
                    </tr>
                    <?php
                    if(count($authors) > 0)
                    {
                        foreach($authors as $author)
                        {
                    ?>
                    <tr>
                       
                        <td><?php echo $author->name ?></td>
                        <td><?php echo $author->nick_name ?></td>
						 <?php if($role == ROLE_ADMIN){ ?>
                        <td><?php echo $author->username ?></td>
						<?php } ?>
                        <td><?php echo $author->gender ?></td>
                       
                      <!--  <td>
                           
                        </td>-->
                     
                    </tr>
                    <?php
                        }
                    }else { ?>
                        <tr align="center">
                            <td colspan="<?php echo ($role == ROLE_ADMIN) ? 5 : 4 ?> ">
                                No authors uploaded
                            </td>
                        </tr>
                    <?php }
                    ?>
                  </table>
                  
                </div><!-- /.box-body -->
              </div><!-- /.box -->
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
    HTMLauthorsElement.prototype.stop = function(){
        this.pause();
        this.currentTime = 0.0;
    }
    function playSound(authors_id) {
        var sounds = document.getElementsByTagName('authors');
        for(i=0; i<sounds.length; i++) sounds[i].stop();
        var authors = document.getElementById(authors_id);
        authors.play();
    }
    function stopSound(authors_id){
     var sounds = document.getElementsByTagName('authors');
        for(i=0; i<sounds.length; i++) sounds[i].stop();
        var authors = document.getElementById(authors_id);
        authors.stop();   
    }
</script>
