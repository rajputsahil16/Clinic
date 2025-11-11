<?php
function generate_dummy_csv($filename = 'patients_demo.csv', $records = 500)
{
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Id', 'Name', 'Gender', 'Age', 'Contact', 'Email', 'Blood Group']);

    $blood_groups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
    $genders = ['Male', 'Female'];
    $names = ['John', 'Alice', 'Bob', 'Sarah', 'David', 'Emma', 'Chris', 'Sophia', 'Liam', 'Olivia'];

    for ($i = 1; $i <= $records; $i++) {
        $name = $names[array_rand($names)];
        $gender = $genders[array_rand($genders)];
        $age = rand(18, 60);
        $contact = '9' . rand(100000000, 999999999);
        $email = strtolower($name) . $i . '@example.com';
        $blood = $blood_groups[array_rand($blood_groups)];

        fputcsv($output, [$i, $name, $gender, $age, $contact, $email, $blood]);
    }

    fclose($output);
    exit;
}
