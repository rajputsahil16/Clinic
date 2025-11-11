<?php defined('BASEPATH') or exit('No direct script access allowed');

class Supplier extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('supplier/index');
    }

    public function fetch_suppliers()
    {
        $suppliers = $this->db->get('suppliers')->result();
        echo json_encode($suppliers);
    }

    public function add_supplier()
    {


        $data = array(
            'name' => $this->input->post('name'),
            'contact' => $this->input->post('contact'),
            'email' => $this->input->post('email'),
            'address' => $this->input->post('address'),
            'gst_no' => $this->input->post('gst_no')
        );

        $exist = $this->db->get_where('suppliers', array('contact' => $data['contact']))->row_array();
        if ($exist) {
            echo json_encode(array('status' => 'error', 'message' => 'Supplier with same contact already exists.'));
            return;
        }
        $this->db->insert('suppliers', $data);
        echo json_encode(array('status' => 'success', 'message' => 'Supplier added successfully.', 'data' => $data));
    }

    public function delete_supplier($id)
    {
        $this->Supplier_model->delete_supplier($id);
        echo json_encode(['status' => 'success', 'message' => 'Deleted Successfully']);
    }

    public function fetch_supplier($id)
    {
        $query = $this->Supplier_model->get_supplier_by_id($id);
        echo json_encode($query);
    }

    public function update_supplier($id)
    {
        $data = array(
            'name' => $this->input->post('editname'),
            'contact' => $this->input->post('editcontact'),
            'email' => $this->input->post('editemail'),
            'address' => $this->input->post('editaddress'),
            'gst_no' => $this->input->post('editgst_no')
        );

        $this->Supplier_model->update_supplier($id, $data);

        echo json_encode(['status' => 'success', 'message' => 'Supplier updated successfully.', 'data' => $data]);
    }

    public function upload_csv()
    {
        if (isset($_FILES['file']['name']) && $_FILES['file']['error'] == 0) {
            $file = $_FILES['file']['tmp_name'];
            $handle = fopen($file, 'r');
            $header = fgetcsv($handle);
            $required_headers = [
                'Id',
                'Name',
                'Contact',
                'Email',
                'Address',
                'Gst_No'
            ];
            if ($header !== $required_headers) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid CSV headers'
                ]);
            }

            $data = [];
            $matched_csv_data = [];
            $matched_system_data = [];
            $i = 0;

            while (($row = fgetcsv($handle)) !== false) {
                $data[] = [
                    'id' => $row[0],
                    'name' => $row[1],
                    'contact' => $row[2],
                    'email' => $row[3],
                    'address' => $row[4],
                    'gst_no' => $row[5]
                ];

                $exist_supplier = $this->Supplier_model->get_supplier_by_name($row[1]);
                if ($exist_supplier) {
                    $matched_csv_data[] = $data[$i];
                    $matched_system_data[] = $exist_supplier;
                }
                $i++;
            }
            fclose($handle);

            echo json_encode([
                'status' => 'success',
                'message' => 'CSV processed successfully',
                'data' => $data,
                'matched_csv_data' => $matched_csv_data,
                'matched_system_data' => $matched_system_data
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No file uploaded or there was an upload error'
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
                $supplier_to_insert = [];
                $supplier_to_update = [];
                $data = 0;

                while (($row = fgetcsv($handle)) !== false) {
                    if ($data == 0) {
                        $data++;
                        continue;
                    }

                    $name = trim($row[1]);
                    $contact = trim($row[2]);
                    $email = trim($row[3]);
                    $address = trim($row[4]);
                    $gst_no = trim($row[5]);

                    if (!empty($name) || !empty($contact)) {
                        $this->db->group_start()
                            ->where('name', $name)
                            ->or_where('contact', $contact)
                            ->group_end();

                        $exist = $this->db->get('suppliers')->row_array();
                        if ($exist) {
                            if ($override) {
                                $supplier_update[] = [
                                    'id' => $exist['id'],
                                    'name' => $name,
                                    'contact' => $contact,
                                    'email' => $email,
                                    'address' => $address,
                                    'gst_no' => $gst_no
                                ];
                                $this->db->where('id', $exist['id'])->update('suppliers', $supplier_update);
                                $supplier_to_update[] = $supplier_update;
                            }
                        } else {
                            $supplier_to_insert[] = [
                                'name' => $name,
                                'contact' => $contact,
                                'email' => $email,
                                'address' => $address,
                                'gst_no' => $gst_no
                            ];
                        }
                    }
                    $data++;
                }
                fclose($handle);
                if (!empty($supplier_to_insert)) {
                    $this->db->insert_batch('suppliers', $supplier_to_insert);
                }

                echo json_encode([
                    'status' => 'success',
                    'message' => 'CSV imported successfully',
                    'inserted_count' => count($supplier_to_insert),
                    'updated_count' => count($supplier_to_update)
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No file selected',
            ]);
        }
    }

    public function export_csv()
    {
        $ids = $this->input->post('ids');

        if (empty($ids)) {
            return;
        }
        $this->db->select('id,name,contact,email,address,gst_no');
        $this->db->from('suppliers');
        $this->db->where_in('id', $ids);
        $query = $this->db->get();
        $suppliers = $query->result_array();

        $filename = "suppliers". "csv";
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        $file = fopen('php://output','w');

        fputcsv($file,array('Id','Name','Contact','Email','Address','GST_No'));

        foreach($suppliers as $row){
            fputcsv($file,$row);
        }

        fclose($file);
        exit;
    }
}
