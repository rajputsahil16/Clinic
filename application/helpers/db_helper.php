<?php

if (!function_exists('get_enum_values')) {
    /**
     * Get ENUM values from a table column
     *
     * @param string $table_name
     * @param string $column_name
     * @return array
     */
    function get_enum_values($table_name, $column_name)
    {
        $CI = &get_instance();
        $query = $CI->db->query("SHOW COLUMNS FROM `$table_name` LIKE '$column_name'");
        $row = $query->row();

        if (!$row) {
            return [];
        }

        // Extract enum values using regex
        preg_match_all("/'(.*?)'/", $row->Type, $matches);
        return $matches[1];
    }
}
