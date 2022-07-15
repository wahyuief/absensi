<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Semester_model extends CI_Model
{
    public $table = 'semester';

    public function get($where = false, $like = false, $limit = false, $offset = false, $order_by = false)
    {
        $order_by || $order_by = 'id_semester DESC';
        if($where) $this->db->where($where);
        if($like) $this->db->like($like);
        if($limit || $offset) $this->db->limit($limit, $offset);
        if($order_by) $this->db->order_by($order_by);
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