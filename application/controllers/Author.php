<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Author extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('author_model');
        
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
        $this->global['pageTitle'] = 'Author';
        $this->load->library('pagination');
        $count = $this->author_model->getAllCount($params);
        $returns = $this->paginationCompress ( "author/", $count, 1 );
        $authors = $this->author_model->getAll($params, $returns["page"], 0);
		
		
        $data['authors'] = $authors;

        $this->loadViews("author", $this->global, $data , NULL);
    }
    
    /**
     * This function is used to load the add new form
     */
    function addNew()
    {
        $data = array();
		$this->load->model('Title_Model');
		$this->load->model('Author_Model');
		$this->load->model('User_model');
		
        if($this->input->post()){
$group1 = '';
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name','Name','trim|required');
            $this->form_validation->set_rules('nick_name','Nick Name','trim|required');
            //$this->form_validation->set_rules('user_id','User Name','trim|required');
            $this->form_validation->set_rules('gender','Gender','trim|required');
            
            $name = ucwords(strtolower($this->security->xss_clean($this->input->post('name'))));
            $nick_name = ucwords(strtolower($this->security->xss_clean($this->input->post('nick_name'))));
            $user_id = $this->input->post('user_id');
			$gender = $this->input->post('gender');
			
			
            if($this->form_validation->run()){
                
                
					
					if(empty($user_id))
					$userId =$this->session->userdata('userId');
				    else
					$userId =(int)$user_id;
					
                    $insertData = [
                        'name' => $name,
                        'nick_name' => $nick_name,
                        'user_id' => $userId,
                        'gender' => $gender,

                        'created_at' => date('Y-m-d h:i:s')
                    ];
                    
                    $this->author_model->addNew($insertData);

                    $this->session->set_flashdata('success', 'Author added successfully.');
                redirect('author');

            }
        }
            
        $this->global['pageTitle'] = 'Add New Author';
        
        
        $data['users'] = $this->User_model->userListing();
		$data['authors'] = $this->Author_Model->getAll();
        
        $this->loadViews("author/addNew", $this->global, $data, NULL);
    }

    function approveAudio($audio_id){
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }else{
            $data['status'] = '1';
			$status = '1';
            $this->author_model->edit($audio_id, $data);
			$userinfo = $this->author_model->getUser($audio_id);
			if(!empty($userinfo))
			{
				$username = $userinfo->name;
				$email = $userinfo->email;
			}
			$this->accept_decline_mail($status,$username,$email);
            $this->session->set_flashdata('success', 'Audio approved successfully.');
            redirect('/story');
        }
    }

    function declineAudio($audio_id){
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }else{
            $data['status'] = '0';
            $status = '0';
            $this->author_model->edit($audio_id, $data);
			$userinfo = $this->author_model->getUser($audio_id);
			if(!empty($userinfo))
			{
				$username = $userinfo->name;
				$email = $userinfo->email;
			}
			$this->accept_decline_mail($status,$username,$email);
            $this->session->set_flashdata('success', 'Audio rejected!');
            redirect('/story');
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