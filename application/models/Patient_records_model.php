<?php

class Patient_records_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all()
    {
        $this->db->select('
            patient_records.id, 
            patients.name AS patient_name,
            patient_records.visit_date,
            patient_records.symptoms,
            patient_records.diagnosis,
            patient_records.prescription
            
        ');
        $this->db->from('patient_records');
        $this->db->join('patients', 'patients.id = patient_records.patient_id', 'left');
        return $this->db->get()->result();
    }   

    public function add_patient_record($data)
    {
        $this->db->insert('patient_records', $data);
        return $this->db->insert_id();
    }

    public function update_patient_record($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('patient_records', $data);
    }

    public function delete_patient_record($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('patient_records');
    }

    public function get_by_id($id)
    {
        $query = $this->db->get_where('patient_records', array('id' => $id));
        return $query->row();
    }

    public function get_by_name($patient_name)
    {
        $this->db->select('patient_records.*,patients.name AS patient_name');
        $this->db->from('patient_records');
        $this->db->join('patients', 'patients.id = patient_records.patient_id', 'left');
        $this->db->where('patients.name', $patient_name);
        $query = $this->db->get();
        return $query->row();
    }
}
