<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pharmacy extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('pharmacy/index');
    }

    public function add_pharmacy()
    {
        $data = array(
            'medicine_name' => $this->input->post('name'),
            "description" => $this->input->post('description'),
            "quantity" => $this->input->post('quantity'),
            "unit_price" => $this->input->post('unit_price'),
            "cost_price" => $this->input->post('cost_price')
        );

        $this->Pharmacy_model->add_pharmacy($data);

        echo json_encode([
            'status' => 'success',
            'message' => 'Pharmacy Added Successfully',
            'data' => $data
        ]);
    }

    public function fetch_data()
    {
        $this->load->model('Pharmacy_model');
        $pharmacy = $this->Pharmacy_model->get_all();
        echo json_encode($pharmacy);
    }

    public function delete_pharmacy($id)
    {
        $this->Pharmacy_model->delete($id);
        echo json_encode(['status' => 'success', 'message' => "Deleted Successfully"]);
    }

    public function update_pharmacy($id)
    {
        $updated_pharmacy = array(
            'medicine_name' => $this->input->post('name'),
            "description" => $this->input->post('description'),
            "quantity" => $this->input->post('quantity'),
            "unit_price" => $this->input->post('unit_price'),
            "cost_price" => $this->input->post('cost_price')
        );
        $this->Pharmacy_model->update($id, $updated_pharmacy);
        echo json_encode(['status' => 'success', 'message' => 'Updated Successfully !', 'data' => $updated_pharmacy]);
    }

    public function get_pharmacy($id)
    {
        $data = $this->Pharmacy_model->get_pharmacy_by_id($id);
        echo json_encode($data);
    }


    public function upload_csv()
    {
        if (isset($_FILES['file']['tmp_name']) && $_FILES['file']['error'] == 0) {
            $file = $_FILES['file']['tmp_name'];
            $handle = fopen($file, "r");
            $header = fgetcsv($handle);
            $require_headers = [
                'Id',
                'Medicine Name',
                'Description',
                'Quantity',
                'Unit Price',
                'Cost Price',
            ];

            if ($header !== $require_headers) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid Headers'
                ]);
                return;
            }

            $data = [];
            $matched_csv_data = [];
            $matched_system_data = [];
            $i = 0;

            while (($row = fgetcsv($handle, 0, ",")) !== FALSE) {

                $data[] = [
                    'id' => $row[0],
                    'medicine_name' => $row[1],
                    'description' => $row[2],
                    'quantity' => $row[3],
                    'unit_price' => $row[4],
                    'cost_price' => $row[5]
                ];

                $exist_pharmacy = $this->Pharmacy_model->get_pharmacy_by_name($row[1]);

                if ($exist_pharmacy) {
                    $matched_csv_data[] = $data[$i];
                    $matched_system_data[] = $exist_pharmacy;
                }
                $i++;
            }
            fclose($handle);

            echo json_encode([
                'status' => 'success',
                'message' => 'Some matched records are matched from csv file !',
                'data' => $data,
                'matched_csv_data' => $matched_csv_data,
                'matched_system_data' => $matched_system_data
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to upload CSV file'
            ]);
        }
    }


    public function import_csv()
    {
        if (!empty($_FILES['file']['name'])) {
            $file = $_FILES['file']['tmp_name'];
            $override = isset($_POST['override']) && $_POST['override'] == 1 ? 1 : 0;
            $handle = fopen($file, 'r');

            if ($file) {
                $pharmacy_to_insert = [];
                $pharmacy_updated = [];
                $pharmacy_data = [];
                $row = 0;

                while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                    if ($row == 0) {
                        $row++;
                        continue;
                    }

                    $medicine_name = trim($data[1]);
                    $description = trim($data[2]);
                    $quantity = trim($data[3]);
                    $unit_price = trim($data[4]);
                    $cost_price = trim($data[5]);

                    if (!empty($medicine_name)) {
                        $this->db->group_start()
                            ->where('medicine_name', $medicine_name)
                            ->or_where('description', $description)
                            ->group_end();

                        $exist = $this->db->get('pharmacy')->row_array();

                        if ($exist) {
                            if ($override == 1) {
                                $pharmacy_data = [
                                    'medicine_name' => $medicine_name,
                                    "description" => $description,
                                    "quantity" => $quantity,
                                    "unit_price" => $unit_price,
                                    "cost_price" => $cost_price
                                ];
                                $this->db->where('id', $exist['id'])->update('pharmacy', $pharmacy_data);
                                $pharmacy_updated[] = $pharmacy_data;
                            }
                        } else {
                            $pharmacy_to_insert[] = [
                                'medicine_name' => $medicine_name,
                                "description" => $description,
                                "quantity" => $quantity,
                                "unit_price" => $unit_price,
                                "cost_price" => $cost_price
                            ];
                        }
                    }
                    $row++;
                }
                fclose($handle);

                if (!empty($pharmacy_to_insert)) {

                    $this->db->insert_batch('pharmacy', $pharmacy_to_insert);
                }

                echo json_encode([
                    'status' => 'success',
                    'message' => 'File imported successfully',
                    'data' => $pharmacy_data
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error while importing file',
                ]);
            }
        }
    }

    public function generate_csv()
    {
        // Load the helper
        $this->load->helper('pharmacy');

        // Path to save CSV file (in uploads folder)
        $file_path = FCPATH . 'uploads/pharmacy_sample.csv';

        // Create the CSV file with 500 records
        generate_pharmacy_csv($file_path, 500);

        // Optional: force download the file
        $this->load->helper('download');
        force_download($file_path, NULL);
    }

    public function search_medicines()
    {
        $query = $this->input->post('query');
        if ($query === null) $query = $this->input->get('query');

        $medicines = $this->Pharmacy_model->search_medicines($query);

        echo json_encode($medicines);
    }

    public function export_csv()
    {
        $ids = $this->input->post('ids');

        if (empty($ids)) {
            return;
        }
        $this->db->select('id,medicine_name,description,quantity,unit_price,cost_price');
        $this->db->from('pharmacy');
        $this->db->where_in('id', $ids);
        $query = $this->db->get();
        $pharmacy = $query->result_array();

        $filename = "selected pharmacy" . date('y-m-d') . "csv";
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        $file = fopen('php://output','w');

        fputcsv($file, array('Id','Medicine Name','Description','Quantity','Unit price','cost price'));

        foreach($pharmacy as $row){
            fputcsv($file,$row);
        }

        fclose($file);
        exit;
    }
}
