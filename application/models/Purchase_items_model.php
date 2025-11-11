<?php

defined('BASEPATH') or exit('No direct script access allowed');

class purchase_items_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Purchase_items_model');
    }

    public function insert_items($purchase_id, $items)
    {
        if (empty($items) || !is_array($items)) {
            return false;
        }

        $successful_inserts = 0;

        foreach ($items as $item) {
            // Validate required fields
            if (!isset($item['medicine_id']) || empty($item['medicine_id'])) {
                log_message('error', 'Missing medicine_id in purchase items data');
                continue;
            }

            $data = [
                'purchase_id' => $purchase_id,
                'medicine_id' => $item['medicine_id'],
                'quantity' => isset($item['quantity']) ? $item['quantity'] : 0,
                'cost_price' => isset($item['cost_price']) ? $item['cost_price'] : 0,
                'subtotal' => isset($item['subtotal']) ? $item['subtotal'] : 0,
            ];

            if ($this->db->insert('purchase_items', $data)) {
                $successful_inserts++;
            }
        }

        return $successful_inserts;
    }

    public function get_items_by_purchase($purchase_id)
    {
        $this->db->where('purchase_id', $purchase_id);
        $query = $this->db->get('purchase_items');
        return $query->result();
    }

    public function delete_items_by_purchase($purchase_id)
    {
        $this->db->where('purchase_id', $purchase_id);
        $this->db->delete('purchase_items');
    }

    public function insert_item($data)
    {
        $this->db->insert('purchase_items', $data);
    }
}
