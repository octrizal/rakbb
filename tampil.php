<?php
include "database_pg.php";
//disini nama database saya adalah nama_database
$result = pg_query("SELECT * FROM master_film limit 50");
// disini saya membuat table dengan nama mahasiswa

echo "<table border='1px'>
<tr><td> nama lokasi</td>
<td> kategori</td></tr>
";
// kolom yang ada di table mahasiswa saya hanya ada 2 yaitu nim dan nama
while ($row = pg_fetch_array($result))
{
echo "<tr>";
echo "<td>".$row['id']."</td>";
echo "<td>".$row['name']."</td>";
echo "</tr>";
}
echo "</table>";
?>