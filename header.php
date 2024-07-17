<?php
//header.php
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inventory Management System</title>
    	<script src="js/jquery-1.10.2.min.js"></script>
		<link rel="stylesheet" href="bootstrap.min.css" />
		<script src="js/jquery.dataTables.min.js"></script>
		<script src="js/dataTables.bootstrap.min.js"></script>		
		<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
		<script src="js/bootstrap.min.js"></script>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <style>
        #searchList {
            position: absolute;
            width: 300px;
            padding: 0;
            list-style-type: none;
            margin: 0;
            border: 1px solid #ccc;
            border-top: none;
            background-color: #f1f1f1;
        }

        #searchList li {
            padding: 8px 12px;
            cursor: pointer;
        }

        #searchList li:hover {
            background-color: #a11828;
        }
    </style>
</head>
<body>
<br/>
<div class="container">
    <!--<h4>Inventori Bahan Baku</h4>-->

    <table width="100%" border="0">
    <tr>
        <td width="37%">
          
            <?php
            // Query untuk mengambil data identitas perusahaan dari tabel tb_identitas
            $queryDataIdentitas = mysqli_query($koneksi, "SELECT * FROM tb_identitas");
            $dataIdentitas = mysqli_fetch_assoc($queryDataIdentitas);

            if ($dataIdentitas) {
                ?>
                <h3><i><b><font color="#CC3300"><?php echo $dataIdentitas['nama_usaha']; ?></font></b></i></h3>
                <font color="#999999"><?php echo $dataIdentitas['alamat']; ?> Telp. <?php echo $dataIdentitas['telp']; ?></font>
                <hr color="#FF0000">
                <?php
            } else {
                echo "Data identitas tidak ditemukan.";
            }
            ?>
        </td>
        <td width="47%">&nbsp;</td>
        <td width="16%">
            <!-- Ganti img source sesuai dengan inputan yang diperoleh -->
             <a href="index.php">
            <?php
            if ($dataIdentitas && isset($dataIdentitas['logo'])) {
                $logoPath = 'uploads/' . $dataIdentitas['logo'];
                ?>
                <img src="<?php echo $logoPath; ?>">
                <?php
            } else {
                echo "Logo tidak ditemukan.";
            }
            ?></a>
        </td>
    </tr>
</table>



    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="index.php" class="navbar-brand">Home</a>
            </div>
            <ul class="nav navbar-nav">
                <?php
                if ($_SESSION['type'] == 'master' || $_SESSION['type'] == 'po' || $_SESSION['type'] == 'user') {
                    ?>
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Purchases</a>
                            <ul class="dropdown-menu">
                                <li><a href="po_input.php">Purchases Order</a></li>
                                <li><a href="po_list.php">Data Purchases Order</a></li>
                            </ul>

                        </li>
                    </ul>
				<?php
                }
				if ($_SESSION['type'] == 'master' || $_SESSION['type'] == 'user') {
                ?>
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Inventory</a>
                            <ul class="dropdown-menu">
                                <li><a href="smginput.php">SMG Input / BPB</a></li>
                                <li><a href="databahan.php">Data BPB</a></li>
                                <li><a href="lokasi_barang.php">Lokasi Barang</a></li>
                            </ul>

                        </li>
                    </ul>
					     <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Cetak Label</a>
                            <ul class="dropdown-menu">
                 
                                <li><a href="generatelabel.php">Generate Label</a></li>
                                <li><a href="cetak_barang.php">Data Cetak Label</a></li>
                            </ul>

                        </li>
                    </ul>
					<ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Data Rak</a>
                            <ul class="dropdown-menu">
                                <li><a href="rakbahan.php">Bahan Baku</a></li>
                            </ul>

                        </li>
                    </ul>
				<?php
				}
				if ($_SESSION['type'] == 'master') {
				?>
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Setting</a>
                            <ul class="dropdown-menu">
                                <li><a href="setting_pt.php">Perusahaan</a></li>
                                <li><a href="user.php">User</a></li>
                            </ul>

                        </li>
                    </ul>
				<?php } ?>	
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span
                                class="label label-pill label-danger count"></span> <?php echo $_SESSION["user_name"]; ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>

        </div>
    </nav>


