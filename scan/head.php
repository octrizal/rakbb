<?php
$link="http://rakbb.indociptawisesa.co.id";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Scan Bahan Baku</title>
    	<script src="<?php echo "$link";?>/js/jquery-1.10.2.min.js"></script>
		<link rel="stylesheet" href="<?php echo "$link";?>/bootstrap.min.css" />
		<script src="<?php echo "$link";?>/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo "$link";?>/js/dataTables.bootstrap.min.js"></script>		
		<link rel="stylesheet" href="<?php echo "$link";?>/css/dataTables.bootstrap.min.css" />
		<script src="<?php echo "$link";?>/js/bootstrap.min.js"></script>
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

                <?php
            } else {
                echo "Data identitas tidak ditemukan.";
            }
            ?>
        </td><hr>
<center>
            <!-- Ganti img source sesuai dengan inputan yang diperoleh -->
            <?php
            if ($dataIdentitas && isset($dataIdentitas['logo'])) {
                $logoPath = $link. '/uploads/' . $dataIdentitas['logo'];
                ?>
                <img src="<?php echo $logoPath; ?>">
                <?php
            } else {
                echo "Logo tidak ditemukan.";
            }
            ?>
			<br>
		
            </center>
            <hr>
        </td>
    </tr>
</table>

        </div>
    </nav>


