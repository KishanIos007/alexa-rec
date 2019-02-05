<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : User_model (User Model)
 * User model class to get to handle user related data 
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Recipy_Model extends CI_Model
{
    protected $table = "tbl_stories";

    function getAll($params = array())
    {
        $this->db->select('stories.*, user.name, user.userId, user.email, category.id as title_id, category.name as category_name');
        $this->db->from($this->table . " as stories");
        $this->db->join('tbl_users as user', 'user.userId = stories.user_id', 'left');
        $this->db->join('tbl_title as category', 'category.id = stories.title_id', 'left');
        
        if(isset($params['user_name']) && !empty($params['user_name'])){
            $this->db->where("user.name like '%" . $params['user_name'] . "%'");
        }
        if(!empty($params['searchText'])) {

            $likeCriteria = "(name LIKE '%".$params['searchText']."%' OR category.name LIKE '%".$params['searchText']."%')";
            $this->db->where($likeCriteria);
        }

        if(isset($params['user_id']) && $params['user_id'] != 1){
            if(isset($params['user_id']) && !empty($params['user_id'])){
                $this->db->where('stories.user_id', $params['user_id']);
            }
        }

        $query = $this->db->get();
        $result = $query->result();        
        return $result;
    }
     function getAllUserInfo($params = array())
    {
        $this->db->select('stories.*, user.name, user.userId, user.email, category.id as title_id, category.name as category_name');
        $this->db->from($this->table . " as stories");
        $this->db->join('tbl_users as user', 'user.userId = stories.user_id', 'left');
        $this->db->join('tbl_title as category', 'category.id = stories.title_id', 'left');
        
        if(isset($params['user_name']) && !empty($params['user_name'])){
            $this->db->where("user.name like '%" . $params['user_name'] . "%'");
        }
        if(!empty($params['searchText'])) {

            $likeCriteria = "(user.name LIKE '%".$params['searchText']."%' OR category.name LIKE '%".$params['searchText']."%')";
            $this->db->where($likeCriteria);
        }

        if(isset($params['user_id']) && $params['user_id'] != 1){
            if(isset($params['user_id']) && !empty($params['user_id'])){
                $this->db->where('stories.user_id', $params['user_id']);
            }
        }

        $query = $this->db->get();
        $result = $query->result();        
        return $result;
    }
    function getAllCount($params = array())
    {
        $this->db->select('stories.*, user.name, user.userId, user.email, category.id as title_id, category.name as category_name');
        $this->db->from($this->table . " as stories");
        $this->db->join('tbl_users as user', 'user.userId = stories.user_id', 'left');
        $this->db->join('tbl_title as category', 'category.id = stories.title_id', 'inner');
        
        if(isset($params['user_name']) && !empty($params['user_name'])){
            $this->db->where("user.name like '%" . $params['user_name'] . "%'");
        }
        if(!empty($params['searchText'])) {

            $likeCriteria = "(user.name LIKE '%".$params['searchText']."%' OR category.name LIKE '%".$params['searchText']."%')";
            $this->db->where($likeCriteria);
        }

        if(isset($params['status'])){
            $this->db->where("stories.status", $params['status']);
        }

        if(isset($params['title_id'])){
            $this->db->where("title_id", $params['title_id']);
        }

        if(isset($params['user_id']) && $params['user_id'] != 1){
            if(isset($params['user_id']) && !empty($params['user_id'])){
                $this->db->where('stories.user_id', $params['user_id']);
            }
        }

        $query = $this->db->get();
        return $query->num_rows();
    }

    function checkCategoryExist($category)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('name', $category);
        $query = $this->db->get();
        
        if($query->num_rows() > 0){
            return true;
        }
        return false;
    }

    function addNew($info)
    {
        $this->db->trans_start();
        $this->db->insert($this->table, $info);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    function edit($stories_id, $storiesInfo)
    {
        $this->db->trans_start();
        
        $this->db->where('id', $stories_id);
        $status = $this->db->update($this->table, $storiesInfo);
        
        $this->db->trans_complete();
        
        return $status;
    }
	function getUser($stories_id)
    {
        $this->db->select('stories.*, user.name, user.userId, user.email');
        $this->db->from($this->table . " as stories");
        $this->db->join('tbl_users as user', 'user.userId = stories.user_id', 'left');
       
            if(!empty($stories_id)){
                $this->db->where('stories.id', $stories_id);
            }
                $query = $this->db->get();
        return $query->row();
	}

}

  