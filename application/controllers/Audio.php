<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Audio extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('audio_model');
        
        $this->isLoggedIn();
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $searchText = $this->security->xss_clean($this->input->post('searchText'));
        
        $params['searchText'] = $data['searchText'] = $searchText;
        $params['user_id'] = $this->session->userdata('userId');
        $this->global['pageTitle'] = 'Audio';
        $this->load->library('pagination');
        $count = $this->audio_model->getAllCount($params);
        $returns = $this->paginationCompress ( "audio/", $count, 1 );
        $audios = $this->audio_model->getAll($params, $returns["page"], 0);
        $data['audios'] = $audios;

        $this->loadViews("audio", $this->global, $data , NULL);
    }
    
    /**
     * This function is used to load the add new form
     */
    function addNew()
    {
        $data = array();
		$this->load->model('Title_Model');
		$this->load->model('user_model');
		
        if($this->input->post()){

            $this->load->library('form_validation');
            $this->form_validation->set_rules('description','Description','trim|required');
            $this->form_validation->set_rules('title_id','Category','trim|required');
            
            $description = ucwords(strtolower($this->security->xss_clean($this->input->post('description'))));
            $title_id = ucwords(strtolower($this->security->xss_clean($this->input->post('title_id'))));
            
            if($this->form_validation->run()){
                
                $config['upload_path']          = './'.UPLOAD_FOLDER;
                $config['allowed_types']        = 'mp3';
                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('file'))
                {
                    $data['error'] = $this->upload->display_errors();
                }
                else
                {
                    $data = array('upload_data' => $this->upload->data());
                }
                
                if(isset($data['error']) && !empty($data['error'])){
                    $this->session->set_flashdata('error', $data['error']);
                }else{
					$title_id =(int)$title_id;
					$userId =$this->session->userdata('userId');
					$userId = (int)$userId;
                    $insertData = [
                        'description' => $description,
                        'file' => "/". UPLOAD_FOLDER . $data['upload_data']['raw_name'] . $data['upload_data']['file_ext'],
                        'status' => PENDING_STATUS,
                        'title_id' => $title_id,
                        'user_id' => $this->session->userdata('userId'),
                        'created_at' => date('Y-m-d h:i:s')
                    ];
                    
                    $this->audio_model->addNew($insertData);
					/**/
					$cate_name = '';
					$user_name = '';
					$category_name = $this->Title_Model->getById($title_id);
					
					if(!empty($category_name))
					{
                        $cate_name = $category_name[0]->name;
					}
					$getUserInfo = $this->user_model->getUserInfo($userId);
					
					if(!empty($getUserInfo))
					{
                        $user_name = $getUserInfo->name;
					}
					if(!empty($user_name) && !empty($cate_name))
					{
					$this->insert_audio_mail($cate_name,$user_name);
					
					}
					
					/**/
					
                    $this->session->set_flashdata('success', 'Audio uploaded successfully.');
                }

            }
        }
            
        $this->global['pageTitle'] = 'Add New Audio';
        
        
        $data['categories'] = $this->Title_Model->getAll();
        
        $this->loadViews("audio/addNew", $this->global, $data, NULL);
    }

    function approveAudio($audio_id){
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }else{
            $data['status'] = '1';
			$status = '1';
            $this->audio_model->edit($audio_id, $data);
			$userinfo = $this->audio_model->getUser($audio_id);
			if(!empty($userinfo))
			{
				$username = $userinfo->name;
				$email = $userinfo->email;
			}
			$this->accept_decline_mail($status,$username,$email);
            $this->session->set_flashdata('success', 'Audio approved successfully.');
            redirect('/audio');
        }
    }

    function declineAudio($audio_id){
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }else{
            $data['status'] = '0';
            $status = '0';
            $this->audio_model->edit($audio_id, $data);
			$userinfo = $this->audio_model->getUser($audio_id);
			if(!empty($userinfo))
			{
				$username = $userinfo->name;
				$email = $userinfo->email;
			}
			$this->accept_decline_mail($status,$username,$email);
            $this->session->set_flashdata('success', 'Audio rejected!');
            redirect('/audio');
        }
    }
	    function insert_audio_mail($cate_name,$user_name){
			
			//$to = 'ikishan.oza@gmail.com';
			$to = 'info@raccontamiunafavola.it';
            $subject = 'raccontamiunafavola : New Audio Insert';
            
            $message = '<html><body>
		        <table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tbody>
			
				<tr>
				<td>
				<div class=email-area style="margin-top:20px;">
				<div class="container email-box" style="background:#E3E3E3; padding:40px;">
				<div class="email-prgp">
				<p>Hello,</p> 
				<p>'. ucfirst($user_name).'  added a new audio in '.$cate_name.' Category.  </p>
				<br/></div>
				<div class="lowr-email-prgph" style="margin-top: 20px;box-sizing: border-box;">
				<p>Regards,<br>The raccontamiunafavola</p></div></div></td></tr><tr><td><div class="email-footer-box" style="margin:0 auto; text-align:center; background:#dc9e0c; padding:10px;color:#fff;"><p style="line-height:3px;"><a href="#" style="color:aqua"></a><br><br></p></div></td></tr></tbody></table>
		  
            </body>
		    </html>';
	

            $header = 'From: info@raccontamiunafavola.it' . "\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";
            $retval = @mail($to, $subject, $message, $header);	
		}
		
		 function accept_decline_mail($status,$user_name='',$to_email){
			$msg = '';
            $to_email =$to_email;
			//$to_email = 'ikishan.oza@gmail.com';
			$user_name = ucfirst($user_name);
			if($status == '1')
			     $msg = ' Your audio has been approved successfully.';
        	if($status == '0')
				 $msg = ' Your audio has been rejected!.';
			$to = $to_email;
			
			if($status == '0' || $status == '1')
			{
            $subject = 'raccontamiunafavola: Audio Status';
            
            $message = '<html><body>
		        <table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tbody>
			
				<tr>
				<td>
				<div class=email-area style="margin-top:20px;">
				<div class="container email-box" style="background:#E3E3E3; padding:40px;">
				<div class="email-prgp">
				<p>Hello,'. $user_name.'</p> 
				<p>'. $user_name.$msg.'</p>
				<br/></div>
				<div class="lowr-email-prgph" style="margin-top: 20px;box-sizing: border-box;">
				<p>Regards,<br>The raccontamiunafavola</p></div></div></td></tr><tr><td><div class="email-footer-box" style="margin:0 auto; text-align:center; background:#dc9e0c; padding:10px;color:#fff;"><p style="line-height:3px;"><a href="#" style="color:aqua"></a><br><br></p></div></td></tr></tbody></table>
		  
            </body>
		    </html>';
	

            $header = 'From: info@raccontamiunafavola.it' . "\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";
            $retval = @mail($to, $subject, $message, $header);	
			}
		}
	
	
}

?>