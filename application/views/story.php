<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Story Management
        <small>Add</small>
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>story/addNew"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">story List</h3>
                    <div class="box-tools">
                        <form action="<?php echo base_url() ?>story" method="POST" id="searchList">
                            <div class="input-group">
                              <input type="text" name="searchText" value="<?php echo $searchText; ?>" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search"/>
                              <div class="input-group-btn">
                                <button class="btn btn-sm btn-default searchList"><i class="fa fa-search"></i></button>
                              </div>
                            </div>
                        </form>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                        <?php if($role == ROLE_ADMIN){ ?>
						<th>Username</th>
                            <th>Email address</th>
                        <?php } ?>
                        <th>Title</th>
                        <th>Tag</th>
                        <th>Length</th>
                        
                        <th>Audio</th>
                        <th>Status</th>
                        <?php if($role == ROLE_ADMIN){ ?>
                            <th>Actions</th>
                        <?php } ?>
                    </tr>
                    <?php
                    if(count($audios) > 0)
                    {
						
                        foreach($audios as $audio)
                        {
							$kindid = $audio->kindid; 
							$kindid = (int)$kindid;
							if($kindid == 0){
						 	$kindinfoo = '';}
						    else
							{								
							$kindinfo = $this->Kind_model->getById($kindid);
							$kindinfoo = $kindinfo[0]->kind_name;
							}
							
							
								
							
                    ?>
                    <tr>
                        <?php if($role == ROLE_ADMIN){ ?>
						<td><?php echo $audio->name ?></td>
                            <td><?php echo $audio->email ?></td>
                        <?php } ?>
                        <td><?php echo $audio->category_name ?></td>
                        <td><?php echo  $kindinfoo ?></td>
                        <td><?php 
						$length =  $audio->length;
						$length = (float)$length;
						
						if($length <= 3000 AND ($length != 0 OR !empty($length))) { echo "Short"; }
						if($length > 3000 AND $length <= 7000) { echo "Medium"; }
						if($length > 7000) { echo "Long"; }
						if($length == 0 OR empty($length)){ echo " "; }
						

						?></td>
                        <!--<td><?php //echo $audio->description ?></td> -->
                        <td>
                            <button class="btn btn-success" onclick="playSound('audio_<?php echo $audio->id ?>')">
                                Play Audio
                            </button>
                            <button  class="btn btn-success" onclick="stopSound('audio_<?php echo $audio->id ?>')">Stop Audio</button>
                            <audio id="audio_<?php echo $audio->id ?>" src="<?php echo base_url() . $audio->file ?>" loop>
                              Your browser does not support the <code>audio</code> element.
                            </audio>
                        </td>
                        <td>
                            <?php 
                            if($audio->status == 0){
                                echo "<span class='label label-danger'>Rejected</span";
                            }
                            if($audio->status == 1){
                                echo "<span class='label label-success'>Approved</span";
                            }
                            if($audio->status == 2){
                                echo "<span class='label label-warning'>Pending</span";
                            }
                            ?>
                        </td>
                        <?php if($role == ROLE_ADMIN){ ?>
                            <td>
                                <?php
                                if($audio->status == 2){    ?>
                                    <a class="btn btn-success" href="<?php echo base_url(); ?>story/approve/<?php echo $audio->id ?>">
                                        Approve
                                    </a>
                                    <a class="btn btn-danger" href="<?php echo base_url(); ?>story/decline/<?php echo $audio->id ?>">
                                        Decline
                                    </a>
                                <?php } 
                                if($audio->status == 1){    ?>
                                    <a class="btn btn-danger" href="<?php echo base_url(); ?>story/decline/<?php echo $audio->id ?>">
                                        Decline
                                    </a>
                                <?php }
                                if($audio->status == 0){    ?>
                                    <a class="btn btn-success" href="<?php echo base_url(); ?>story/approve/<?php echo $audio->id ?>">
                                        Approve
                                    </a>
                                <?php } ?>
                            </td>
                        <?php } ?>
                    </tr>
                    <?php
                        }
                    }else { ?>
                        <tr align="center">
                            <td colspan="<?php echo ($role == ROLE_ADMIN) ? 5 : 4 ?> ">
                                No Audio uploaded
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
    HTMLAudioElement.prototype.stop = function(){
        this.pause();
        this.currentTime = 0.0;
    }
    function playSound(audio_id) {
        var sounds = document.getElementsByTagName('audio');
        for(i=0; i<sounds.length; i++) sounds[i].stop();
        var audio = document.getElementById(audio_id);
        audio.play();
    }
    function stopSound(audio_id){
     var sounds = document.getElementsByTagName('audio');
        for(i=0; i<sounds.length; i++) sounds[i].stop();
        var audio = document.getElementById(audio_id);
        audio.stop();   
    }
</script>
