<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Purchase extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Supplier_model');
        $this->load->model('Purchase_model');
        $this->load->model('Pharmacy_model');
        $this->load->model('Purchase_items_model');
    }

    public function index()
    {
        $data['suppliers'] = $this->Supplier_model->get_all_suppliers();
        $this->load->view('purchase/index', $data);
    }

    public function get_all_purchase()
    {
        $query = $this->Purchase_model->get_all_purchases();
        echo json_encode($query);
    }

    public function add()
    {
        $data['suppliers'] = $this->Supplier_model->get_all_suppliers();
        $data['medicines'] = $this->Pharmacy_model->get_all();
        $this->load->view('purchase/add_purchase', $data);
    }

    public function add_purchase()
    {

        $input = $this->input->raw_input_stream;
        $data = json_decode($input, true);

        if (!is_array($data)) {
            $data = [
                'supplier_id' => $this->input->post('supplier_id'),
                'purchase_date' => $this->input->post('purchase_date'),
                'payment_status' => $this->input->post('payment_status'),
                'remarks' => $this->input->post('remarks'),
                'items' => $this->input->post('items'),
                'total_amount' => $this->input->post('grand_total')
            ];
        }

        $supplier_id = $data['supplier_id'];
        $purchase_date = $data['purchase_date'];
        $payment_status = $data['payment_status'];
        $remarks = $data['remarks'];
        $items = $data['items'];
        $grand_total = $data['total_amount'];


        if (empty($supplier_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Supplier id is empty']);
            return;
        }

        if (empty($items)) {
            echo json_encode(['status' => 'error', 'message' => 'Items are empty ']);
            return;
        }

        $purchase_data = [
            'supplier_id' => $supplier_id,
            'purchase_date' => $purchase_date,
            'payment_status' => $payment_status,
            'remarks' => $remarks,
            'total_amount' => $grand_total

        ];

        $this->db->insert('purchases', $purchase_data);
        $purchase_id = $this->db->insert_id();

        if (!$purchase_id) {
            echo json_encode(['status' => 'error', 'message' => 'Purchase id is not found']);
        }

        foreach ($items as $item) {

            if (!isset($item['medicine_id']) || empty($item['medicine_id'])) {
                echo json_encode(['status' => 'error', 'message' => 'medicine id is not found']);
            }

            $purchase_item = [
                'purchase_id' => $purchase_id,
                'medicine_id' => $item['medicine_id'],
                'quantity' => $item['orderqty'],
                'cost_price' => $item['cost_price'],
                'subtotal' => $item['total']
            ];
            $this->Purchase_items_model->insert_item($purchase_item);
        }

        echo json_encode(['status' => 'success', 'message' => 'Purchase Item Added Successfully !']);
    }


    public function update_purchase($id)
    {
        $input = $this->input->raw_input_stream;
        $data = json_decode($input, true);

        $supplier_id = $data['supplier_id'];
        $purchase_date = $data['purchase_date'];
        $payment_status = $data['payment_status'];
        $remarks = $data['remarks'];
        $items = $data['items'];
        $grand_total = $data['total_amount'];

        if (empty($supplier_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Supplier id is empty']);
            return;
        }

        if (empty($items)) {
            echo json_encode(['status' => 'error', 'message' => 'Items are empty']);
            return;
        }

        $purchase_data = [
            'supplier_id' => $supplier_id,
            'purchase_date' => $purchase_date,
            'payment_status' => $payment_status,
            'remarks' => $remarks,
            'total_amount' => $grand_total
        ];
        $this->db->where('id', $id);
        $this->db->update('purchases', $purchase_data);

        $existing_items = $this->db->select('id')->get_where('purchase_items', ['purchase_id' => $id])->result_array();
        $existing_ids = array_column($existing_items, 'id');

        $updated_ids = [];

        foreach ($items as $item) {
            $purchase_item = [
                'purchase_id' => $id,
                'medicine_id' => $item['medicine_id'],
                'quantity' => $item['orderqty'],
                'cost_price' => $item['cost_price'],
                'subtotal' => $item['total']
            ];

            if (isset($item['id']) && !empty($item['id'])) {
                $this->db->where('id', $item['id']);
                $this->db->update('purchase_items', $purchase_item);
                $updated_ids[] = $item['id'];
            } else {
                $this->db->insert('purchase_items', $purchase_item);
                $updated_ids[] = $this->db->insert_id();
            }
        }

        if (!empty($existing_ids)) {
            $ids_to_delete = array_diff($existing_ids, $updated_ids);
            if (!empty($ids_to_delete)) {
                $this->db->where_in('id', $ids_to_delete);
                $this->db->delete('purchase_items');
            }
        }

        echo json_encode(['status' => 'success', 'message' => 'Purchase updated successfully!']);
    }






    public function get_purchase($id)
    {
        $purchase = $this->db->get_where('purchases', ['id' => $id])->row_array();

        $this->db->select('purchase_items.*, pharmacy.medicine_name as medicine_name');
        $this->db->from('purchase_items');
        $this->db->join('pharmacy', 'pharmacy.id = purchase_items.medicine_id', 'left');
        $this->db->where('purchase_items.purchase_id', $id);
        $items = $this->db->get()->result_array();

        $response = [
            'purchase' => $purchase,
            'items' => $items
        ];

        echo json_encode($response);
    }


    public function edit_purchase($id)
    {
        $data['id']  = $id;
        $data['suppliers'] = $this->Supplier_model->get_all_suppliers();
        $this->load->view('purchase/edit_purchase', $data);
    }

    public function delete_purchase($id)
    {
        $this->db->where('id', $id);
        $this->db->update('purchases', ['status' => 1]);

        // $this->db->where('purchase_id',$id);
        // $this->db->delete('purchase_items');

        echo json_encode([
            'status' => 'success',
            'message' => 'Purchase status successfully changed !'
        ]);
    }

    public function export_selected()
    {
        $ids = $this->input->post('ids');

        if (empty($ids)) {
            return;
        }

        $this->db->select('id,supplier_id,purchase_date,total_amount,payment_status,remarks,status');
        $this->db->from('purchases');
        $this->db->where_in('id', $ids);
        $query = $this->db->get();
        $purchases = $query->result_array();    

        // CSV Headers
        $filename = "selected_purchases_" . date('Y-m-d') . ".csv";
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        // Open output stream
        $file = fopen('php://output', 'w');

        // CSV column headings
        fputcsv($file, array('ID', 'Supplier ID', 'Purchase Date','Total Amount', 'Payment Status', 'Remarks','status'));

        // Data rows
        foreach ($purchases as $row) {
            fputcsv($file, $row);
        }

        fclose($file);
        exit;
    }
}
