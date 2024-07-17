<?php
//user.php

include('database_connection.php');


?>
		
                   			<table width="100%" class="table table-bordered table-striped">
                   				<thead>
									<tr>
									  <th>No</th>
									  <th>Kode</th>
									  <th>Nama</th>
								  </tr>
								  <?php
								  $q=mysqli_query($koneksi,"select * from gudang_master");
								  while($ar=mysqli_fetch_array($q)){
								   
								   echo "<tr>
										<th>$ar[kode]</th>
										<th>$ar[nama]</th>
										<th>$ar[create]</th>
									</tr>";
								   
								   } ?>
								</thead>
                   			</table>
                   		