<?php
//user.php

include('database_connection.php');

if(!isset($_SESSION["type"]))
{
	header('location:login.php');
}

if($_SESSION["type"] != 'master')
{
	header("location:index.php");
}

include('header.php');


?>

		
		
       
		
<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Input Bahan </strong></div>
				<div class="panel-body" align="center">
				 <form method="get" id="name" action="databahan.php">
						<table width="100%" border="0">
  <tr>
    <td width="37%">&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="62%">&nbsp;</td>
  </tr>
  <tr>
    <td><div class="form-group">
							<label>Masukan Data Bahan</label>
							<input type="text" name="nama" id="name" class="form-control"  required />
							
						</div></td>
    <td>&nbsp;</td>
    <td><input type="submit" name="proses" value="Proses Data" class="btn-success"/></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

                     
        			
        		</form>                   		
					
				</div>
			</div>
		</div>
        
<?php
include('footer.php');
?>
