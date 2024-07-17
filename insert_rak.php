<?php
// insert_rak.php

include('database_connection.php');

if (isset($_POST['action']) && $_POST['action'] == 'Add') {
    // Lakukan pengecekan apakah data sudah ada atau belum
    $check_query = "SELECT COUNT(*) AS total FROM rak_sub WHERE nama = :nama AND id_rak = :id_rak";
    $check_statement = $connect->prepare($check_query);
    $check_statement->execute([
        ':nama' => $_POST['nama'],
        ':id_rak' => $_POST['id_rak']
    ]);
    $result = $check_statement->fetch(PDO::FETCH_ASSOC);

    // Jika data sudah ada, berikan tanggapan
    if ($result['total'] > 0) {
        echo "Data sudah ada dalam database.";
    } else {
        // Jika data belum ada, lanjutkan dengan operasi INSERT
        $user_query = "SELECT user_email FROM user_details WHERE user_id = :user_id";
        $user_statement = $connect->prepare($user_query);
        $user_statement->execute(array(':user_id' => $_SESSION["user_id"]));
        $user_result = $user_statement->fetch();

        $query = "
        INSERT INTO rak_sub (nama, id_rak, kapasitas, terpakai, date_create, created_by, ket) 
        VALUES (:nama, :id_rak, :kapasitas, :terpakai, :date_create, :created_by, :ket)
        ";
        $statement = $connect->prepare($query);
        $statement->execute([
            ':nama' => $_POST['nama'],
            ':id_rak' => $_POST['id_rak'],
            ':kapasitas' => $_POST['kapasitas'],
            ':terpakai' => 0,
            ':date_create' => date("Y-m-d H:i:s"),
            ':created_by'  => $user_result['user_email'],
            ':ket' => $_POST['ket']
        ]);

        $last_id = $connect->lastInsertId();

        $new_row_query = "
            SELECT rak_sub.*, rak.id_gudang 
            FROM rak_sub 
            JOIN rak ON rak_sub.id_rak = rak.id_rak 
            WHERE rak_sub.id = :id
        ";
        $new_row_statement = $connect->prepare($new_row_query);
        $new_row_statement->execute([':id' => $last_id]);
        $new_row = $new_row_statement->fetch();

        echo '
        <tr>
            <td>'.$new_row["nama"].'</td>
            <td>'.$new_row["id_gudang"].'</td>
            <td>'.$new_row["kapasitas"].'</td>
            <td>'.$new_row["terpakai"].'</td>
            <td>'.$new_row["date_create"].'</td>
            <td>'.$new_row["created_by"].'</td>
            <td>'.$new_row["ket"].'</td>
        </tr>
        ';
    }
}
?>
