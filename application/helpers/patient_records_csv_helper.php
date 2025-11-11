<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('download_patient_records_csv')) {
    function download_patient_records_csv($records = 100) // you can change number of records
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="patient_records_' . date('Ymd_His') . '.csv"');

        $output = fopen('php://output', 'w');

        // ✅ Header row (added Contact)
        fputcsv($output, ['Id', 'Patient Name', 'Contact', 'Visit Date', 'Symptoms', 'Diagnosis', 'Prescription']);

        // Sample data arrays
        $names = ['Liam', 'Olivia', 'Noah', 'Emma', 'Ava', 'Sophia', 'Isabella', 'Mason', 'Lucas', 'Mia'];
        $symptoms = ['fever', 'cough', 'headache', 'fatigue', 'nausea', 'sore throat', 'cold', 'back pain'];
        $diagnosis = ['Flu', 'Covid-19', 'Allergy', 'Migraine', 'Food Poisoning', 'Asthma'];
        $prescriptions = ['Paracetamol', 'Ibuprofen', 'Cough Syrup', 'Antibiotic', 'Rest and Fluids'];

        // ✅ Generate random records with increment ID
        for ($i = 1; $i <= $records; $i++) {
            $name = $names[array_rand($names)];
            $contact = '9' . rand(100000000, 999999999); // random 10-digit number
            $visit_date = date('Y-m-d', strtotime('-' . rand(0, 30) . ' days'));
            $symptom = $symptoms[array_rand($symptoms)];
            $diag = $diagnosis[array_rand($diagnosis)];
            $pres = $prescriptions[array_rand($prescriptions)];

            fputcsv($output, [$i, $name, $contact, $visit_date, $symptom, $diag, $pres]);
        }

        fclose($output);
        exit; // Important: stops further CI output
    }
}
