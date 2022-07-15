<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Kmm_model extends CI_Model
{
    public $table = 'kelas_matkul_mahasiswa';

    public function get($where = false, $like = false, $limit = false, $offset = false, $order_by = false)
    {
        $this->db->select('kelas_matkul_mahasiswa.id_kmm, kelas_matkul_mahasiswa.id_mahasiswa, kelas_matkul.*, kelas.*, mata_kuliah.*, users.*, semester.*');
        $order_by || $order_by = 'id_kmm DESC';
        if($where) $this->db->where($where);
        if($like) $this->db->like($like);
        if($limit || $offset) $this->db->limit($limit, $offset);
        if($order_by) $this->db->order_by($order_by);
        $this->db->join('kelas_matkul', 'kelas_matkul_mahasiswa.id_km = kelas_matkul.id_km', 'LEFT');
        $this->db->join('kelas', 'kelas_matkul.id_kelas = kelas.id_kelas', 'LEFT');
        $this->db->join('mata_kuliah', 'kelas_matkul.id_matkul = mata_kuliah.id_matkul', 'LEFT');
        $this->db->join('users', 'mata_kuliah.id_dosen = users.id', 'LEFT');
        $this->db->join('semester', 'mata_kuliah.id_semester = semester.id_semester', 'LEFT');
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