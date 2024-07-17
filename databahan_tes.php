<?php
include('database_connection.php');

if(!isset($_SESSION["type"])) {
    header('location:login.php');
}

if($_SESSION["type"] != 'master') {
    header("location:index.php");
}

include('header.php');

try {
    $conn = new PDO('mysql:host=localhost;dbname=inventori_bahan_baku', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query untuk mengambil data dari tabel bahan_baku
    $bahanBakuQuery = "SELECT * FROM bahan_baku";
    $bahanBakuResult = $conn->query($bahanBakuQuery);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null; // Tutup koneksi setelah selesai menggunakan database
?>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h3 {
            color: #4285f4;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
            background-color: #fff;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .entries-container, .search-container {
            float: left;
            margin-bottom: 20px;
        }

        .search-container {
            float: right;
            margin-bottom: 20px;
        }

        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            clear: both;
            margin-top: 20px;
        }

        .action-buttons button {
            padding: 8px 12px;
            margin: 4px;
        }

        @media only screen and (max-width: 768px) {
            .entries-container, .search-container {
                float: none;
                text-align: center;
                margin-bottom: 10px;
            }

            .pagination-container {
                flex-direction: column;
                align-items: center;
            }
        }

        .action-buttons button:hover {
            background-color: #4285f4;
            color: #fff;
        }
    </style>
    <script>
        function changeEntries(entries) {
            // Add logic here to handle the number of entries selected
            console.log(entries);
        }

        function searchTable() {
            // Add logic here to handle the search functionality
            var input = document.getElementById("searchInput").value;
            console.log(input);
        }

        function changePage(direction) {
            // Add logic here to handle previous and next page
            console.log(direction);
        }
    </script>

<div class="row">
<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>View Data</strong></div>
				<div class="panel-body" align="center">
				
<div class="entries-container">
    <label for="entries">Show Entries:</label>
    <select id="entries" onChange="changeEntries(this.value)">
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
    </select>
</div>

<div class="search-container">
    <label for="searchInput">Search:</label>
    <input type="text" id="searchInput" onKeyUp="searchTable()" placeholder="Search...">
</div>

<div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                            	<h2 class="panel-title">Data Bahan Baku</h2>
                            </div>
                            <div class="panel-body">
                   		<div class="row"><div class="col-sm-12 table-responsive">
                   			<table id="koderak_data" class="table table-bordered table-striped">
    <tr>
        <th>Kode BPB</th>
        <th>Source Document</th>
        <th>Validation Date</th>
        <th>Customer/Vendor</th>
        <th>Destination Location</th>
        <th>Edit</th>
        <th>Data</th>
    </tr>
    </div>
                   	</div>
               	</div>
    
    <?php while ($row = $bahanBakuResult->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td><?php echo $row['Kode_BPB']; ?></td>
            <td><?php echo $row['Source_Document']; ?></td>
            <td><?php echo $row['Validation_Date']; ?></td>
            <td><?php echo $row['Customer_Vendor']; ?></td>
            <td><?php echo $row['Destination_Location']; ?></td>
            <td class="action-buttons">
                <button class="edit-btn" onClick="editData(<?php echo $row['id']; ?>)">
                    <i class="fas fa-edit"></i> Update
                </button>
            </td>
            <td class="action-buttons">
                <button class="data-btn"> <a href="dataitem.php">Detail</a></button>
            </td>
        </tr>
    <?php } ?>
</table>

<div class="pagination-container">
    <div class="pagination-text">
        Showing 1 to 4 of 4 entries.
    </div>
    <div class="pagination-buttons">
        <button onClick="changePage('previous')">Previous</button>
        <button onClick="changePage('next')">Next</button>
    </div>
</div>


				</div>
			</div>
	 </div>
</div>     
<?php
include('footer.php');
?>

