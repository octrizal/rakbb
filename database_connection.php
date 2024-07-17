<?php
//database_connection.php

$connect = new PDO('mysql:host=localhost;dbname=inventori_bahan_baku', 'root', '');
$koneksi = mysqli_connect("localhost","root","","inventori_bahan_baku");
session_start();

//setting waktu
// $url='http://192.168.9.251/rakinventori';
$url='https://rakbb.indociptawisesa.co.id';

date_default_timezone_set('Asia/Jakarta');
$tgl_waktu=date("Y-m-d H:i:s");
$tgl_skg=date("Y-m-d");

?>