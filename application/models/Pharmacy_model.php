<?php

class Pharmacy_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all()
    {
        $query = $this->db->get('pharmacy');
        return $query->result();
    }

    public function add_pharmacy($data)
    {
        return $this->db->insert('pharmacy', $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('pharmacy', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('pharmacy');
    }

    public function get_pharmacy_by_id($id)
    {
        $query = $this->db->get_where('pharmacy', array('id' => $id));
        return $query->row();
    }

    public function get_pharmacy_by_name($medicine_name)
    {
        $query = $this->db->get_where('pharmacy', array('medicine_name' => $medicine_name));
        return $query->row();
    }

    public function search_medicines($query)
    {
        if (empty($query)) return []; // return empty result if no query

        $this->db->like('medicine_name', $query);
        $this->db->limit(10);
        $query = $this->db->get('pharmacy');
        return $query->result();
    }
}
