<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Title extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Title_Model');
        
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
            $this->global['pageTitle'] = 'title';
            $this->load->library('pagination');
            $count = $this->Title_Model->getAllCount($searchText);
            $returns = $this->paginationCompress ( "title/", $count, 1 );
            $title = $this->Title_Model->getAll($searchText, $returns["page"], 0);
            $data['title'] = $title;
            $this->loadViews("title", $this->global, $data , NULL);
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
                $this->form_validation->set_rules('name','Category Name','trim|required|max_length[128]');
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('name'))));
                if($this->form_validation->run()){
                    if($this->Title_Model->checkCategoryExist($name)){
                        $this->session->set_flashdata('error', 'category already exist');
                    }else{
                        $this->Title_Model->addNew(array('name' => $name));
                        redirect('/title');
                    }
                }
            }
            
            $this->global['pageTitle'] = 'Add New Category';
            $data = array();
            $this->loadViews("title/addNew", $this->global, $data, NULL);
        }
    }

    function deleteCategory()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $this->load->model('story_model');
            $categoryId = $this->input->post('categoryId');
            $count = $this->story_model->getAllCount(array('title_id' => $categoryId));
            
            if($count > 0){
                echo(json_encode(array('status'=>'exist')));
            }else{
                $result = $this->Title_Model->delete($categoryId);
                if ($result) { echo(json_encode(array('status'=>TRUE))); }
                else { echo(json_encode(array('status'=>FALSE))); }
                
            }
            
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