<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Category extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('category_model');
        
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
            $this->global['pageTitle'] = 'Categories';
            $this->load->library('pagination');
            $count = $this->category_model->getAllCount($searchText);
            $returns = $this->paginationCompress ( "categories/", $count, 1 );
            $categories = $this->category_model->getAll($searchText, $returns["page"], 0);
            $data['categories'] = $categories;
            $this->loadViews("categories", $this->global, $data , NULL);
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
                    if($this->category_model->checkCategoryExist($name)){
                        $this->session->set_flashdata('error', 'category already exist');
                    }else{
                        $this->category_model->addNew(array('name' => $name));
                        redirect('/categories');
                    }
                }
            }
            
            $this->global['pageTitle'] = 'Add New Category';
            $data = array();
            $this->loadViews("categories/addNew", $this->global, $data, NULL);
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
            $this->load->model('audio_model');
            $categoryId = $this->input->post('categoryId');
            $count = $this->audio_model->getAllCount(array('category_id' => $categoryId));
            
            if($count > 0){
                echo(json_encode(array('status'=>'exist')));
            }else{
                $result = $this->category_model->delete($categoryId);
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
                redirect('categories');
            }
            
            $data['category'] = $this->category_model->getById($categoryId);
            
            $this->global['pageTitle'] = 'Edit Category';
            
            $this->loadViews("categories/addNew", $this->global, $data, NULL);
        }
    }

}

?>