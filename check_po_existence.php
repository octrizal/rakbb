<?php

// check_po_existence.php

include('database_connection.php');

if (isset($_POST['po_code'])) {
    $po_code = $_POST['po_code'];
    $query = mysqli_query($connection, "SELECT COUNT(*) as count FROM po_list WHERE kode_po='$po_code'");
    $result = mysqli_fetch_array($query);

    echo $result['count'] > 0 ? 'exists' : 'not_exists';
}
?>

