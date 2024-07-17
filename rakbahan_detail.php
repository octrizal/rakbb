<?php
// rakbahan_detail.php

include('database_connection.php');

// Pastikan pengguna sudah login
if (!isset($_SESSION["type"])) {
    header('location:login.php');
    exit(); // Pastikan menghentikan eksekusi lebih lanjut setelah redirect
}

include('header.php');

// Ambil id_rak dari parameter GET
if (isset($_GET['id_rak'])) {
    $id_rak = $_GET['id_rak'];
} else {
    header("location:rakbahan.php");
    exit(); // Pastikan menghentikan eksekusi lebih lanjut setelah redirect
}

try {
    // Ambil id_gudang berdasarkan id_rak
    $query = "SELECT id_gudang FROM rak WHERE id_rak = :id_rak";
    $statement = $connect->prepare($query);
    $statement->execute([':id_rak' => $id_rak]);
    $id_gudang = $statement->fetchColumn();

    // Ambil nama rak berdasarkan id_rak
    $query_nama_rak = "SELECT nama FROM rak WHERE id_rak = :id_rak";
    $statement_nama_rak = $connect->prepare($query_nama_rak);
    $statement_nama_rak->execute([':id_rak' => $id_rak]);
    $nama_rak = $statement_nama_rak->fetchColumn();

    // Ambil semua data rak_sub untuk id_rak tertentu
    $query_rak_sub = "SELECT * FROM rak_sub WHERE id_rak = :id_rak";
    $statement_rak_sub = $connect->prepare($query_rak_sub);
    $statement_rak_sub->execute([':id_rak' => $id_rak]);
    $rak_sub_data = $statement_rak_sub->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Tangkap error PDO jika terjadi
    die("Error: " . $e->getMessage());
}

?>

<span id="alert_action"></span>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="row">
                <div class="panel-heading">
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                        <h3 class="panel-title">Detail Rak <?php echo htmlspecialchars($nama_rak); ?></h3>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align="right">
    <button type="button" name="add" id="add_button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addRakSubModal">Tambah Subrak</button>
    <a href="rakbahan.php"><button type="button" name="kembali" class="btn btn-danger btn-xs">Kembali</button></a>
</div>

                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12 table-responsive">
                        <table id="raksub_data" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Kode Gudang</th>
                                    <th>Kapasitas</th>
                                    <th>Terpakai</th>
                                    <th>Date Create</th>
                                    <th>Create By</th>
                                    <th>Keterangan</th>
                                    <th>Action</th> <!-- Kolom untuk tombol edit -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rak_sub_data as $row) { ?>
                                    <tr>
                                        <td><?php echo $row["nama"]; ?></td>
                                        <td><?php echo $id_gudang; ?></td>
                                        <td><?php echo $row["kapasitas"]; ?></td>
                                        <td><?php echo $row["terpakai"]; ?></td>
                                        <td><?php echo $row["date_create"]; ?></td>
                                        <td><?php echo $row["created_by"]; ?></td>
                                        <td><?php echo $row["ket"]; ?></td>
                                        <td>
                                            <!-- Tombol edit dengan data-target ke modal edit -->
                                            <button type="button" class="btn btn-warning btn-xs edit_button" data-toggle="modal" data-target="#editRakModal" 
                                                data-id="<?php echo $row['id_rak_sub']; ?>" 
                                                data-nama="<?php echo $row['nama']; ?>" 
                                                data-kapasitas="<?php echo $row['kapasitas']; ?>" 
                                                data-ket="<?php echo $row['ket']; ?>">Edit</button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Tambah Data -->
<div id="addRakSubModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tambah Data Rak Sub</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="add_rak_sub_form">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Kapasitas</label>
                        <input type="text" name="kapasitas" id="kapasitas" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="ket" id="ket" class="form-control" required></textarea>
                    </div>
                    <div class="form-group" align="center">
                        <input type="hidden" name="id_rak" id="id_rak" value="<?php echo $id_rak; ?>" />
                        <input type="hidden" name="action" id="action" value="Add" />
                        <input type="submit" name="submit" id="submit" class="btn btn-success" value="Tambah" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal Edit -->
<div id="editRakModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Data Rak</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="edit_rak_form">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="edit_nama" id="edit_nama" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Kapasitas</label>
                        <input type="text" name="edit_kapasitas" id="edit_kapasitas" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="edit_ket" id="edit_ket" class="form-control" required></textarea>
                    </div>
                    <div class="form-group" align="center">
                        <input type="hidden" name="edit_id_rak_sub" id="edit_id_rak_sub" />
                        <input type="hidden" name="edit_action" id="edit_action" value="Edit" />
                        <input type="submit" name="edit_submit" id="edit_submit" class="btn btn-info" value="Edit" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>

<script>

$(document).ready(function() {
    // Ajax untuk mengirim form tambah data
    $('#add_rak_sub_form').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: "insert_rak.php",
            method: "POST",
            data: $(this).serialize(),
            success: function(data) {
                $('#add_rak_sub_form')[0].reset();
                $('#addRakSubModal').modal('hide');
                $('#alert_action').html('<div class="alert alert-success">Data berhasil ditambahkan.</div>').fadeIn().delay(2000).fadeOut();
                setTimeout(function() {
                    location.reload(true); // Refresh halaman secara otomatis
                }, 2000);
            }
        });
    });
});

$(document).ready(function() {
    $('#raksub_data').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true
    });

    // Mengisi nilai form edit saat tombol edit ditekan
    $(document).on('click', '.edit_button', function() {
        var id_rak_sub = $(this).data('id');
        var nama = $(this).data('nama');
        var kapasitas = $(this).data('kapasitas');
        var ket = $(this).data('ket');
        $('#edit_id_rak_sub').val(id_rak_sub);
        $('#edit_nama').val(nama);
        $('#edit_kapasitas').val(kapasitas);
        $('#edit_ket').val(ket);
    });

    // Ajax untuk mengirim form edit
    $('#edit_rak_form').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: "update_rak_sub.php",
            method: "POST",
            data: $(this).serialize(),
            success: function(data) {
                $('#edit_rak_form')[0].reset();
                $('#editRakModal').modal('hide');
                $('#alert_action').html('<div class="alert alert-success">Data berhasil diubah.</div>').fadeIn().delay(2000).fadeOut();
                setTimeout(function() {
                    location.reload(true); // Refresh halaman secara otomatis
                }, 2000);
            }
        });
    });
});
</script>
