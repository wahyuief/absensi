<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Foto_model extends CI_Model
{
    public $table = 'users_photos';

    public function get($where = false, $like = false, $limit = false, $offset = false, $order_by = false)
    {
        $this->db->select('users_photos.photo, users.*');
        $order_by || $order_by = 'users_photos.id DESC';
        if($where) $this->db->where($where);
        if($like) $this->db->like($like);
        if($limit && $offset) $this->db->limit($limit, $offset);
        if($order_by) $this->db->order_by($order_by);
        $this->db->join('users', 'users_photos.id_user = users.id');
        $query = $this->db->get($this->table);
        return $query;
    }

    public function add($data)
    {
        $this->db->set($data);
        $this->db->insert($this->table);
        return $this->db->insert_id();
    }

    public function set($data, $where)
    {
        $this->db->set($data);
        $this->db->where($where);
        return $this->db->update($this->table);
    }

    public function unset($where)
    {
        $this->db->where($where);
        return $this->db->delete($this->table);
    }
}