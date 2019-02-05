<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Kind extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kind_Model');
        
        $this->isLoggedIn();
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;
            $this->global['pageTitle'] = 'Kind';
            $this->load->library('pagination');
            $count = $this->Kind_Model->getAllCount($searchText);
            $returns = $this->paginationCompress ( "kind/", $count, 1 );
            $kinds = $this->Kind_Model->getAll($searchText, $returns["page"], 0);
            $data['kinds'] = $kinds;
            $this->loadViews("kind", $this->global, $data , NULL);
        }
    }
    
    /**
     * This function is used to load the add new form
     */
    function addNew()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            if($this->input->post()){
                $this->load->library('form_validation');
                $this->form_validation->set_rules('kind_name','Kind Name','trim|required');
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('kind_name'))));
                $notes = $this->input->post('notes');
				if($this->form_validation->run()){
                    if($this->Kind_Model->checkCategoryExist($name)){
                        $this->session->set_flashdata('error', 'kind already exist');
                    }else{
						$data = array('kind_name' => $name,'notes'=>$notes);
                        $this->Kind_Model->addNew($data);
                        redirect('/kind');
                    }
                }
            }
            
            $this->global['pageTitle'] = 'Add New Kind';
            $data = array();
            $this->loadViews("kind/addNew", $this->global, $data, NULL);
        }
    }

    function deleteKind()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $this->load->model('Kind_Model');
             $categoryId = $this->input->post('kindId');
            $result = $this->Kind_Model->delete($categoryId);
                if ($result) { echo(json_encode(array('status'=>TRUE))); }
/*		   $count = $this->Kind_Model->getAllCount(array('kindid' => $categoryId));
            
            if($count > 0){
                echo(json_encode(array('status'=>'exist')));
            }else{
                $result = $this->Kind_Model->delete($categoryId);
                if ($result) { echo(json_encode(array('status'=>TRUE))); }
                else { echo(json_encode(array('status'=>FALSE))); }
                
            }*/
            
        }
    }

    function editOld($categoryId = NULL)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            if($categoryId == null)
            {
                redirect('title');
            }
            
            $data['category'] = $this->Title_Model->getById($categoryId);
            
            $this->global['pageTitle'] = 'Edit Category';
            
            $this->loadViews("title/addNew", $this->global, $data, NULL);
        }
    }

}

?>