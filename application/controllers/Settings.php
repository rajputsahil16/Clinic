<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends CI_Controller
{
    public function default_settings()
    {
        // Get all tables in the database
        $tables = $this->db->list_tables();
        $this->load->view('settings/default_settings', ['tables' => $tables]);
    }

    public function get_enum_fields()
    {
        $table = $this->input->post('table');
        $fields = [];
        $columns = $this->db->query("SHOW COLUMNS FROM `$table`")->result();
        foreach ($columns as $col) {
            if (strpos($col->Type, 'enum(') === 0) {
                $fields[] = $col->Field;
            }
        }
        echo json_encode($fields);
    }


    public function save_enum()
    {
        $table_name = $this->input->post('table_name');
        $field_name = $this->input->post('field_name');
        $enum_values = $this->input->post('enum_values');

        // Prepare enum values for SQL
        $enum_array = array_map('trim', explode(',', $enum_values));
        $enum_sql = "'" . implode("','", $enum_array) . "'";

        // Build ALTER TABLE query
        $sql = "ALTER TABLE `$table_name` MODIFY `$field_name` ENUM($enum_sql)";

        if ($this->db->query($sql)) {
            echo json_encode(['message' => 'Enum values updated successfully!']);
        } else {
            echo json_encode(['message' => 'Failed to update enum values. SQL: ' . $sql]);
        }
    }



    public function get_enum_values()
    {
        $table = $this->input->post('table');
        $field = $this->input->post('field');
        $query = $this->db->query("SHOW COLUMNS FROM `$table` LIKE '$field'");
        $row = $query->row();
        $enum_values = [];
        if ($row) {
            preg_match("/^enum\((.*)\)$/", $row->Type, $matches);
            if (isset($matches[1])) {
                $enum_values = array_map(function ($v) {
                    return trim($v, "'");
                }, explode(',', $matches[1]));
            }
        }
        echo json_encode(['enum_values' => implode(' , ', $enum_values)]);
    }
}
