<?php
include('database_connection.php');

if(isset($_POST["query"])) {
    $output = '';
    $query = "SELECT id_bpb, bpb FROM bahan_baku WHERE bpb LIKE '%".$_POST["query"]."%'";
    $result = mysqli_query($koneksi, $query);
    $output = '<ul class="list-unstyled">';
    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_array($result)) {
            $output .= '<li>'.$row["id_bpb"].' - '.$row["bpb"].'</li>';
        }
    } else {
        $output .= '<li>BPB Not Found</li>';
    }
    $output .= '</ul>';
    echo $output;
}
?>
