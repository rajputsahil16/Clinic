<?php

class Patient_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_patients()
    {
        $query = $this->db->get('patients');
        return $query->result();
    }

    public function get_patient_by_id($id)
    {
        $query = $this->db->get_where('patients', array('id' => $id));
        return $query->row();
    }

    public function get_patient_by_name($name)
    {
        $query = $this->db->get_where('patients', array('name' => $name));
        return $query->result();
    }

    public function get_patient_by_contact($contact)
    {
        $query = $this->db->get_where('patients', array('contact' => $contact));
        return $query->row();
    }


    public function add_patient($data)
    {
        if (is_array($data) && isset($data[0]) && is_array($data[0])) {
            $this->db->insert_batch('patients', $data);
            return $this->db->insert_id(); // returns the ID of the first inserted record
        } else {
            $data['created_by'] = $this->session->userdata('user_id');
            $this->db->insert('patients', $data);
            return $this->db->insert_id(); // ✅ return inserted ID
        }
    }



    public function update_patient($id, $data)
    {
        $data['updated_by'] = $this->session->userdata('user_id');
        $this->db->where('id', $id);
        return $this->db->update('patients', $data);
    }

    public function delete_patient($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('patients');
    }
}
