<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : User_model (User Model)
 * User model class to get to handle user related data 
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Category_Model extends CI_Model
{
    protected $table = "tbl_categories";

    function getAll($searchText = "")
    {
        $this->db->select('*');
        $this->db->from($this->table);
        if(!empty($searchText)) {
            $likeCriteria = "(name  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    function getById($id)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        if(!empty($id)) {
            $this->db->where('id', $id);
        }
        
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    function getAllCount($searchText = "")
    {
        $this->db->select('*');
        $this->db->from($this->table);
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

    function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
        
        return true;
    }

    function editCategory($userInfo, $userId)
    {
        $this->db->where('userId', $userId);
        $this->db->update($this->table, $userInfo);
        
        return TRUE;
    }
    

}

  