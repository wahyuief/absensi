<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Matkul_model extends CI_Model
{
    public $table = 'mata_kuliah';

    public function get($where = false, $like = false, $limit = false, $offset = false, $order_by = false)
    {
        $this->db->select('mata_kuliah.*, semester.*, users.*');
        $order_by || $order_by = 'id_matkul DESC';
        if($where) $this->db->where($where);
        if($like) $this->db->like($like);
        if($limit || $offset) $this->db->limit($limit, $offset);
        if($order_by) $this->db->order_by($order_by);
        $this->db->join('semester', 'mata_kuliah.id_semester = semester.id_semester');
        $this->db->join('users', 'mata_kuliah.id_dosen = users.id');
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