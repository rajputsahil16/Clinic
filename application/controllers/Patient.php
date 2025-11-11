<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Patient extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();
    }

    public function index()
    {
        $this->load->view('patient/index');
    }

    public function generate_csv()
    {
        $this->load->helper('csv');
        generate_dummy_csv();
    }

    public function fetch_patients()
    {
        $patients = $this->Patient_model->get_all_patients();
        echo json_encode($patients);
    }


    public function add_patient()
    {

        $name = $this->input->post('name');
        $gender = $this->input->post('gender');
        $age = $this->input->post('age');
        $contact = $this->input->post('contact');
        $email = $this->input->post('email');
        $blood_group = $this->input->post('blood_group');

        $this->db->where('name', $name);
        $this->db->where('contact', $contact);
        $exist = $this->db->get('patients')->row_array();

        if ($exist) {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => 'Patient with same name and contact already exists',
                ]
            );
            return;
        }

        $data = array(
            'name'  => $name,
            'gender' => $gender,
            'age' => $age,
            'contact' => $contact,
            'email' => $email,
            'blood_group' => $blood_group
        );
        $this->Patient_model->add_patient($data);
        echo json_encode(
            [
                'status' => 'success',
                'message' => 'Patient added successfully',
                'data' => $data
            ]
        );
    }

    public function delete_patient($id)
    {
        $this->Patient_model->delete_patient($id);
        echo json_encode(['status' => 'success', 'message' => 'Patient deleted successfully']);
    }

    public function update_patient($id)
    {
        $data = array(
            'name' => $this->input->post('name'),
            'gender' => $this->input->post('gender'),
            'age' => $this->input->post('age'),
            'contact' => $this->input->post('contact'),
            'email' => $this->input->post('email'),
            'blood_group' => $this->input->post('blood_group')
        );
        $this->Patient_model->update_patient($id, $data);
        echo json_encode(['status' => 'success', 'message' => 'Patient updated successfully', 'data' => $data]);
    }

    public function get_patient($id)
    {
        $patient = $this->Patient_model->get_patient_by_id($id);
        echo json_encode($patient);
    }

    public function upload_csv()
    {
        if (isset($_FILES['file']['tmp_name']) && $_FILES['file']['error'] == 0) {
            $file = $_FILES['file']['tmp_name'];
            $handle = fopen($file, "r");
            $header = fgetcsv($handle);
            $required_headers = [
                'Id',
                'Name',
                'Gender',
                'Age',
                'Contact',
                'Email',
                'Blood Group'
            ];

            if ($header !== $required_headers) {
                echo json_encode(
                    [
                        'status' => 'error',
                        'message' => 'Invalid CSV headers'
                    ]
                );
                return;
            }

            $data = [];
            $matched_csv_data = [];
            $matched_system_data = [];
            $i = 0;

            while (($row = fgetcsv($handle, 500, ',')) !== FALSE) {

                $data[] = [
                    'id' => $row[0],
                    'name' => $row[1],
                    'gender' => $row[2],
                    'age' => $row[3],
                    'contact' => $row[4],
                    'email' => $row[5],
                    'blood_group' => $row[6]
                ];


                $existing_patient = $this->Patient_model->get_patient_by_contact($row[4]);

                if ($existing_patient) {
                    $matched_csv_data[] = $data[$i];
                    $matched_system_data[] = $existing_patient;
                }

                $i++;
            }

            fclose($handle);
            echo json_encode(
                [
                    'status' => 'success',
                    'message' => 'Some matched records are matched from csv file',
                    'data' => $data,
                    'matched_csv_data' => $matched_csv_data,
                    'matched_system_data' => $matched_system_data
                ]
            );
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => 'Failed to upload CSV file'
                ]
            );
        }
    }


    public function import_csv()
    {
        if (!empty($_FILES['file']['name'])) {
            $override = isset($_POST['override']) && $_POST['override'] == 1 ? 1 : 0;

            $handle = $_FILES['file']['tmp_name'];
            $file = fopen($handle, 'r');

            if ($file) {
                $patients_to_insert = [];
                $patients_updated = [];
                $row = 0;

                while (($data = fgetcsv($file, 0, ",")) !== FALSE) {
                    if ($row == 0) {
                        $row++;
                        continue;
                    }

                    $name = trim($data[1]);
                    $gender = trim($data[2]);
                    $age = trim($data[3]);
                    $contact = trim($data[4]);
                    $email = trim($data[5]);
                    $blood_group = trim($data[6]);

                    if (!empty($name) || !empty($contact)) {
                        $this->db->group_start()
                            ->where('name', $name)
                            ->where('contact', $contact)
                            ->group_end();

                        $existing = $this->db->get('patients')->row_array();

                        if ($existing) {
                            if ($override == 1) {
                                $update_data = [
                                    'name' => $name,
                                    'gender' => $gender,
                                    'age' => $age,
                                    'contact' => $contact,
                                    'email' => $email,
                                    'blood_group' => $blood_group,
                                ];
                                $this->db->where('id', $existing['id'])->update('patients', $update_data);
                                $patients_updated[] = $update_data;
                            }
                        } else {
                            $patients_to_insert[] = [
                                'name' => $name,
                                'gender' => $gender,
                                'age' => $age,
                                'contact' => $contact,
                                'email' => $email,
                                'blood_group' => $blood_group,
                            ];
                        }
                    }

                    $row++;
                }

                fclose($file);

                if (!empty($patients_to_insert)) {
                    $this->db->insert_batch('patients', $patients_to_insert);
                }

                echo json_encode([
                    'status' => 'success',
                    'message' => 'File processed successfully',
                    'override' => $override == 1 ? 'enabled' : 'disabled',
                    'inserted_count' => count($patients_to_insert),
                    'updated_count' => count($patients_updated),
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
        $this->db->select('id,name,gender,age,contact,email,blood_group');
        $this->db->from('patients');
        $this->db->where_in('id', $ids);
        $query = $this->db->get();
        $patients = $query->result_array();

        $filename = "selected_patients_" . date('Y-m-d') . ".csv";
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        $file = fopen('php://output','w');

        fputcsv($file, array('ID', 'Name', 'Gender','Age', 'Contact', 'Email','Blood Group'));

        foreach ($patients as $row) {
            fputcsv($file, $row);
        }

        fclose($file);
        exit;

    }
}
