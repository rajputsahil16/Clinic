<?php


class Purchase_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_purchases()
    {
        $this->db->select('
       purchases.*,
       suppliers.name AS supplier_name
       ');
        $this->db->from('purchases');
        $this->db->join('suppliers', 'suppliers.id = purchases.supplier_id', 'left');
        $this->db->where('purchases.status', 0);
        return $this->db->get()->result();
    }

    public function add_purchase($data)
    {
        return $this->db->insert('purchases', $data);
    }

    public function update_purchase($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('purchases', $data);
    }

    public function delete_purchase($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('purchases');
    }

    public function get_purchase_by_id($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('purchases');
        return $query->row();
    }
}
