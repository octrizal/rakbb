<?php
error_reporting(error_reporting() & ~E_NOTICE);
// Set header type konten.
header("Content-Type: application/json; charset=UTF-8");

include "database_pg.php";


// Deklarasi variable keyword buah.
$data = $_GET["query"];

// Query ke database.
$query  = pg_query("SELECT * FROM master_film where name like '%".$data."%'");

// Fetch hasil query.
$result = pg_fetch_array($query);

// Cek apakah ada yang cocok atau tidak.
if (count($result) > 0) {
    foreach($result as $data) {
        $output['suggestions'][] = [
            'value' => $data['name'],
            'name'  => $data['name']
        ];
    }

    // Encode ke JSON.
    echo json_encode($output);

// Jika tidak ada yang cocok.
} else {
    $output['suggestions'][] = [
        'value' => '',
        'name'  => ''
    ];

    // Encode ke JSON.
    echo json_encode($output);
}
