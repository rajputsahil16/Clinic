<?php defined('BASEPATH') or exit('No direct script access allowed');

class Patient_records extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Patient_records_model');
        $this->load->model('Patient_model');
    }

    public function index()
    {
        $data['patients'] = $this->Patient_model->get_all_patients();

        $this->load->view('patient_records/index', $data);
    }

    public function add_patient_records()
    {
        $data = [
            'patient_id' => $this->input->post('patients'),
            'visit_date' => $this->input->post('visitdate'),
            'symptoms' => $this->input->post('symptoms'),
            'diagnosis' => $this->input->post('diagnosis'),
            'prescription' => $this->input->post('prescription')
        ];

        $this->Patient_records_model->add_patient_record($data);
        echo json_encode([
            'status' => 'success',
            'message' => 'Patient Record Added SuccessFully !',
            'data' => $data
        ]);
    }

    public function fetch_data()
    {
        try {
            $result = $this->Patient_records_model->get_all();
            $query = $this->db->last_query();

            // Debug information
            $debug_info = [
                'result_count' => count($result),
                'query' => $query,
                'result_sample' => !empty($result) ? $result[0] : 'No data'
            ];

            echo json_encode([
                'status' => 'success',
                'message' => 'Data Fetched Successfully !',
                'data' => $result,
                'debug' => $debug_info
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error fetching data: ' . $e->getMessage(),
                'data' => []
            ]);
        }
    }

    public function delete_patient_record($id)
    {
        $this->Patient_records_model->delete_patient_record($id);
        echo json_encode(['status' => 'success', 'message' => "Deleted Successfully"]);
    }

    public function get_patient_by_id($id)
    {
        $data = $this->Patient_records_model->get_by_id($id);
        echo json_encode([
            'status' => 'success',
            'message' => 'Data Fetched Successfully !',
            'data' => $data
        ]);
    }

    public function update_patient_record($id)
    {
        $updated_data = [
            'patient_id' => $this->input->post('patients'),
            'visit_date' => $this->input->post('visitdate'),
            'symptoms' => $this->input->post('symptoms'),
            'diagnosis' => $this->input->post('diagnosis'),
            'prescription' => $this->input->post('prescription')
        ];
        $this->Patient_records_model->update_patient_record($id, $updated_data);
        echo json_encode([
            'status' => 'success',
            'message' => 'Updated Successfully !',
            'data' => $updated_data
        ]);
    }

    public function export_csv()
    {
        $this->load->helper('patient_records_csv');
        download_patient_records_csv(50); // generate and download 50 random records
    }

    public function upload_csv()
    {
        if (isset($_FILES['file']['name']) && $_FILES['file']['error'] == 0) {
            $file = $_FILES['file']['tmp_name'];
            $handle = fopen($file, 'r');
            $header = fgetcsv($handle); // assuming first row is header 
            $required_headers = ['Id', 'Patient Name', 'Visit Date', 'Symptoms', 'Diagnosis', 'Prescription'];
            if ($header !== $required_headers) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid CSV format']);
                return;
            }
            $data = [];
            $matched_csv_data = [];
            $matched_system_data = [];
            $i = 0;

            while (($row = fgetcsv($handle, 0, ",")) !== FALSE) {
                $data[] = [
                    'id' => $row[0],
                    'patient_name' => $row[1],
                    'visit_date' => $row[2],
                    'symptoms' => $row[3],
                    'diagnosis' => $row[4],
                    'prescription' => $row[5]
                ];

                $exist_patient_record = $this->Patient_records_model->get_by_name($row[1]);

                if ($exist_patient_record) {
                    $matched_csv_data[] = $data[$i];
                    $matched_system_data[] = $exist_patient_record;
                }
                $i++;
            }
            fclose($handle);
            echo json_encode([
                'status' => 'success',
                'message' => 'CSV uploaded successfully',
                'data' => $data,
                'matched_csv_data' => $matched_csv_data,
                'matched_system_data' => $matched_system_data
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to upload csv file'
            ]);
        }
    }

    // ...existing code...
    public function import_csv()
    {
        if (empty($_FILES['file']['name'])) {
            echo json_encode(['status' => 'error', 'message' => 'No file uploaded']);
            return;
        }

        $file = $_FILES['file']['tmp_name'];
        $override = isset($_POST['override']) && $_POST['override'] == 1 ? 1 : 0;

        $handle = @fopen($file, 'r');
        if (!$handle) {
            echo json_encode(['status' => 'error', 'message' => 'Unable to open uploaded file']);
            return;
        }

        // Read header (header-wise)
        $rawHeader = fgetcsv($handle);
        if (!$rawHeader || !is_array($rawHeader)) {
            fclose($handle);
            echo json_encode(['status' => 'error', 'message' => 'CSV header missing or invalid']);
            return;
        }

        // Normalize headers: trim, lowercase, single-space
        $headers = array_map(function ($h) {
            $h = trim($h);
            $h = preg_replace('/\s+/', ' ', $h);
            return strtolower($h);
        }, $rawHeader);

        // required header candidates
        $name_keys = ['patient name', 'name', 'full name'];
        $contact_keys = ['contact', 'contact number', 'phone', 'phone number', 'mobile'];

        // helper to find header index by candidates
        $find_index = function ($candidates) use ($headers) {
            foreach ($candidates as $k) {
                $idx = array_search($k, $headers, true);
                if ($idx !== false) return $idx;
            }
            return null;
        };

        $idx_name = $find_index($name_keys);
        $idx_contact = $find_index($contact_keys);

        if ($idx_name === null || $idx_contact === null) {
            fclose($handle);
            echo json_encode(['status' => 'error', 'message' => 'CSV must include Patient Name and Contact columns (header-wise)']);
            return;
        }

        // optional columns
        $idx_visit = array_search('visit date', $headers, true);
        if ($idx_visit === false) $idx_visit = array_search('date', $headers, true);
        $idx_symptoms = array_search('symptoms', $headers, true);
        $idx_diagnosis = array_search('diagnosis', $headers, true);
        $idx_prescription = array_search('prescription', $headers, true);

        $created_patients = [];
        $inserted_records = [];
        $updated_records = [];
        $skipped = [];
        $row_number = 1; // header

        while (($row = fgetcsv($handle, 0, ",")) !== FALSE) {
            $row_number++;

            $patient_name = isset($row[$idx_name]) ? trim($row[$idx_name]) : '';
            $contact = isset($row[$idx_contact]) ? trim($row[$idx_contact]) : '';

            if ($patient_name === '' || $contact === '') {
                $skipped[] = ['row' => $row_number, 'reason' => 'Missing patient name or contact'];
                continue;
            }

            // optional fields
            $visit_date = ($idx_visit !== false && isset($row[$idx_visit])) ? trim($row[$idx_visit]) : null;
            $symptoms = ($idx_symptoms !== false && isset($row[$idx_symptoms])) ? trim($row[$idx_symptoms]) : null;
            $diagnosis = ($idx_diagnosis !== false && isset($row[$idx_diagnosis])) ? trim($row[$idx_diagnosis]) : null;
            $prescription = ($idx_prescription !== false && isset($row[$idx_prescription])) ? trim($row[$idx_prescription]) : null;

            $visit_date = $visit_date === '' ? null : $visit_date;
            $symptoms = $symptoms === '' ? null : $symptoms;
            $diagnosis = $diagnosis === '' ? null : $diagnosis;
            $prescription = $prescription === '' ? null : $prescription;

            // --- IMPORTANT: lookup ONLY by contact to allow same name + different contacts ---
            $patient_row = $this->db->where('contact', $contact)->get('patients')->row_array();

            // create patient if not found (use model if available)
            if (empty($patient_row)) {
                $new_patient = ['name' => $patient_name, 'contact' => $contact];
                $patient_id = null;

                if (method_exists($this->Patient_model, 'add_patient')) {
                    $res = $this->Patient_model->add_patient($new_patient);
                    if (is_numeric($res) && $res > 0) {
                        $patient_id = (int)$res;
                    } elseif (is_array($res) && isset($res['id'])) {
                        $patient_id = (int)$res['id'];
                    } elseif (is_object($res) && isset($res->id)) {
                        $patient_id = (int)$res->id;
                    }
                }

                if (!$patient_id) {
                    $this->db->insert('patients', $new_patient);
                    $patient_id = (int)$this->db->insert_id();
                }

                if (!$patient_id) {
                    $skipped[] = ['row' => $row_number, 'reason' => 'Failed to create patient'];
                    continue;
                }

                $created_patients[] = ['id' => $patient_id, 'name' => $patient_name, 'contact' => $contact];
            } else {
                $patient_id = (int)$patient_row['id'];
            }

            // check existing patient_records by patient_id + visit_date (if provided)
            $exist = false;
            if ($visit_date !== null) {
                $exist = $this->db->where([
                    'patient_id' => $patient_id,
                    'visit_date' => $visit_date
                ])->get('patient_records')->row_array();
            }

            $record_data = [
                'patient_id' => $patient_id,
                'visit_date' => $visit_date,
                'symptoms' => $symptoms,
                'diagnosis' => $diagnosis,
                'prescription' => $prescription
            ];

            if ($exist && isset($exist['id'])) {
                if ($override == 1) {
                    if (method_exists($this->Patient_records_model, 'update_patient_record')) {
                        $this->Patient_records_model->update_patient_record($exist['id'], $record_data);
                    } else {
                        $this->db->where('id', $exist['id'])->update('patient_records', $record_data);
                    }
                    $record_data['id'] = $exist['id'];
                    $updated_records[] = $record_data;
                } else {
                    $skipped[] = ['row' => $row_number, 'reason' => 'Record exists and override not set'];
                }
            } else {
                // insert record (use model if available)
                $insert_id = null;
                if (method_exists($this->Patient_records_model, 'add_patient_record')) {
                    $res = $this->Patient_records_model->add_patient_record($record_data);
                    if (is_numeric($res) && $res > 0) {
                        $insert_id = (int)$res;
                    } elseif (is_array($res) && isset($res['id'])) {
                        $insert_id = (int)$res['id'];
                    } elseif (is_object($res) && isset($res->id)) {
                        $insert_id = (int)$res->id;
                    }
                }

                if (!$insert_id) {
                    $this->db->insert('patient_records', $record_data);
                    $insert_id = (int)$this->db->insert_id();
                }

                if ($insert_id) {
                    $record_data['id'] = $insert_id;
                    $inserted_records[] = $record_data;
                } else {
                    $skipped[] = ['row' => $row_number, 'reason' => 'Failed to insert patient record'];
                }
            }
        }

        fclose($handle);

        echo json_encode([
            'status' => 'success',
            'message' => 'File processed',
            'summary' => [
                'patients_created' => count($created_patients),
                'records_inserted' => count($inserted_records),
                'records_updated' => count($updated_records),
                'rows_skipped' => count($skipped)
            ],
            'created_patients' => $created_patients,
            'inserted_records' => $inserted_records,
            'updated_records' => $updated_records,
            'skipped_rows' => $skipped
        ]);
    }

    public function exports_csv()
    {
        $ids = $this->input->post('ids');

        if (empty($ids)) {
            return;
        }
        $this->db->select('id,patient_id,visit_date,symptoms,diagnosis,prescription');
        $this->db->from('patient_records');
        $this->db->where_in('id', $ids);
        $query = $this->db->get();
        $patient_records = $query->result_array();

        $filename = "Patient_Records" . date('y-m-d') . "csv";
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        $file = fopen('php://output','w');

        fputcsv($file, array('Id','Patient_Id','Visit_date','Symptoms','Diagnosis','Prescription'));

        foreach($patient_records as $row){
            fputcsv($file,$row);
        }

        fclose($file);  
        exit;
    }
}
