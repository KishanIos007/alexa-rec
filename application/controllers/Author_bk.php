<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Author (AuthorController)
 * Login class to control to authenticate user credentials and starts user's session.
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
		$this->load->helper('url_helper');
        $this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
			
            $this->global['pageTitle'] = 'Author';
			$data = array();
            $this->loadViews("authors_list", $this->global, $data, NULL);
        }
    } 
	function addAuthor()
    {
        $data = array();
		
		
        if($this->input->post()){

            
            $this->form_validation->set_rules('auhorname','Author Name','trim|required');
            $this->form_validation->set_rules('authornickname','Author Name','trim|required');
            
            $auhorname = ucwords(strtolower($this->security->xss_clean($this->input->post('auhorname'))));
            $authornickname = ucwords(strtolower($this->security->xss_clean($this->input->post('authornickname'))));
            $gender = $this->input->post('gender');
            
            if($this->form_validation->run()){
                
        
             
					
					$userId =$this->session->userdata('userId');
					$userId = (int)$userId;
                    $insertData = [
                        'name' => $auhorname,
                        'nick_name' => $authornickname,
                        'gender' => $gender,
                        'created_at' => date('Y-m-d h:i:s')
                    ];
                    
                    $this->story_model->addNew($insertData);
					
					
                    $this->session->set_flashdata('success', 'Author added successfully.');
                

            }
        }
            
         $this->global['pageTitle'] = 'Author';
			$data = array();
            $this->loadViews("author/addNewAuthor", $this->global, $data, NULL);
    }

	public function addAuthor1()
    {
		 
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
			if(!empty($this->input->post()))
			{  
		    
			//$this->load->library('form_validation');
            //$this->form_validation->set_rules('auhorname','Author Name','trim|required|max_length[128]');
           // $this->form_validation->set_rules('authornickname','Author Name','trim|required|max_length[128]');
            
		
			
                echo $name = ucwords($this->input->post('auhorname'));
               die; if($this->form_validation->run()){
                    if($this->author_model->checkAuthorExist($name)){
                        $this->session->set_flashdata('error', 'Author already exist');
                    }else{
						$authornickname = $this->input->post('authornickname');
						$gender = $this->input->post('gender');
						$dataa = array('name' => $name,'nick_name' => $authornickname,'gender' => $gender);
                        $this->author_model->addNew($dataa);
                        redirect('/index');
                    }
                }
           
			
			}
			
			
            $this->global['pageTitle'] = 'Author';
			$data = array();
            $this->loadViews("addNewAuthor", $this->global, $data, NULL);
        }
    }
  
     public function register()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            if($this->input->post()){
                $this->load->library('form_validation');
            
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[128]|trim');
                $this->form_validation->set_rules('nick_name','Nick Name','trim|required|max_length[128]');
                $this->form_validation->set_rules('gender','Gender','trim|required');               
			   $this->form_validation->set_rules('password', 'Password', 'required|max_length[32]');
                $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
                $this->form_validation->set_rules('name', 'Name', 'required|max_length[128]|trim');
                $this->form_validation->set_rules('age', 'Age', 'required');
                
                if($this->form_validation->run())
                {
                    $email = strtolower($this->security->xss_clean($this->input->post('email')));
                    if($this->login_model->checkEmailExist($email)){
                        $this->session->set_flashdata('error', 'Email already exist!');
                    }else{
                        $password = $this->input->post('password');
                        $age = strtolower($this->security->xss_clean($this->input->post('age')));
                        $name = strtolower($this->security->xss_clean($this->input->post('name')));
                        $nick_name = $this->input->post('nick_name');
                        $gender = $this->input->post('gender');                       
					    $insertData = [
                            "email" => $email,
							"nick_name"=>$nick_name,
							"gender"=>$gender,
                            "password" => getHashedPassword($password),
                            "age" => $age,
                            "name" => $name,
                            "roleId" => ROLE_MANAGER,
                            'createdDtm' => date('Y-m-d h:i:s')
                        ];
                        $register = $this->login_model->register($insertData);
                        $result = $this->login_model->loginMe($email, $password);
                        
                        if($register)
                        {
                            $this->session->set_flashdata('success', 'Registration successfull');
                            redirect('/dashboard');
                        }
                        else
                        {
                            $this->session->set_flashdata('error', 'Email or password mismatch');
                        }
                    }
                }
            }
            
            $this->load->view('register');
        }
        else
        {
            redirect('/dashboard');
        }
    }
    
 
	}

?>