<?php 
 
    class Supplier_model extends CI_Model
    {
        public function __construct()
        {
            parent::__construct();

        }

        public function get_all_suppliers()
        {
            $query = $this->db->get('suppliers');
            return $query->result();
        }

        // public function add_supplier($data)
        // {
        //     return $this->db->insert('suppliers', $data);
        // }

        public function add_supplier($data)
    {
        if (is_array($data) && isset($data[0]) && is_array($data[0])) {
            $this->db->insert_batch('suppliers', $data);
            return $this->db->insert_id(); // returns the ID of the first inserted record
        } else {
            $this->db->insert('suppliers', $data);
            return $this->db->insert_id();
        }
    }

        public function update_supplier($id,$data)
        {
            $this->db->where('id',$id);
            return $this->db->update('suppliers',$data);
        }

        public function delete_supplier($id)
        {
            $this->db->where('id',$id);
            return $this->db->delete('suppliers');
        }

        public function get_supplier_by_id($id)
        {
            $query = $this->db->get_where('suppliers', array('id'=> $id));
            return $query->row();
        }

        public function get_supplier_by_name($name)
        {
            $query = $this->db->get_where('suppliers', array('name'=> $name));
            return $query->row();
        }

    }