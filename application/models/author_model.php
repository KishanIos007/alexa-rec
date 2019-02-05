<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : User_model (User Model)
 * User model class to get to handle user related data 
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Author_Model extends CI_Model
{
    protected $table = "tbl_authors";

    function getAll($params = array())
    {
        $this->db->select('authors.*, user.name as username, user.userId, user.email');
        $this->db->from($this->table . " as authors");
        $this->db->join('tbl_users as user', 'user.userId = authors.user_id', 'left');
        
        if(isset($params['user_name']) && !empty($params['user_name'])){
            $this->db->where("user.name like '%" . $params['user_name'] . "%'");
        }
        if(!empty($params['searchText'])) {

            $likeCriteria = "(name LIKE '%".$params['searchText']."%')";
            $this->db->where($likeCriteria);
        }

        if(isset($params['user_id']) && $params['user_id'] != 1){
            if(isset($params['user_id']) && !empty($params['user_id'])){
                $this->db->where('authors.user_id', $params['user_id']);
            }
        }

        $query = $this->db->get();
        $result = $query->result();        
        return $result;
    }

    function getAllCount($params = array())
    {
        $this->db->select('authors.*, user.name, user.userId, user.email');
        $this->db->from($this->table . " as authors");
        $this->db->join('tbl_users as user', 'user.userId = authors.user_id', 'left');
       
        
        if(isset($params['user_name']) && !empty($params['user_name'])){
            $this->db->where("user.name like '%" . $params['user_name'] . "%'");
        }
        if(!empty($params['searchText'])) {

            $likeCriteria = "(name LIKE '%".$params['searchText']."%')";
            $this->db->where($likeCriteria);
        }

        if(isset($params['status'])){
            $this->db->where("authors.status", $params['status']);
        }

       

        if(isset($params['user_id']) && $params['user_id'] != 1){
            if(isset($params['user_id']) && !empty($params['user_id'])){
                $this->db->where('authors.user_id', $params['user_id']);
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

    function edit($authors_id, $authorsInfo)
    {
        $this->db->trans_start();
        
        $this->db->where('id', $authors_id);
        $status = $this->db->update($this->table, $authorsInfo);
        
        $this->db->trans_complete();
        
        return $status;
    }
	function getUser($authors_id)
    {
        $this->db->select('authors.*, user.name, user.userId, user.email');
        $this->db->from($this->table . " as authors");
        $this->db->join('tbl_users as user', 'user.userId = authors.user_id', 'left');
       
            if(!empty($authors_id)){
                $this->db->where('authors.id', $authors_id);
            }
                $query = $this->db->get();
        return $query->row();
	}

}

  