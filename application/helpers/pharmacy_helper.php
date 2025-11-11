<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('generate_pharmacy_csv')) {
    function generate_pharmacy_csv($file_path = 'pharmacy_sample.csv', $record_count = 500)
    {
        $fp = fopen($file_path, 'w');

        // Write the CSV header
        fputcsv($fp, ['Id', 'Medicine Name', 'Description', 'Quantity', 'Unit Price', 'Cost Price']);

        for ($i = 1; $i <= $record_count; $i++) {
            $medicine_name = 'Medicine ' . $i;
            $description = 'Sample description for ' . $medicine_name;
            $quantity = rand(1, 500);
            $unit_price = rand(10, 200);
            $cost_price = $unit_price * $quantity;

            fputcsv($fp, [$i, $medicine_name, $description, $quantity, $unit_price, $cost_price]);
        }

        fclose($fp);
        return $file_path;
    }
}
