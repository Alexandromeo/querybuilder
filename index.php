<?php
include "back_index.php";
$crud = new back_index();
?>

<form method="post" action="">
	Nama : <input type="text" name="nama"><br/>
	email :<input type="text" name="email"><br/>
	<input type="submit" name="tambah">
</form>

<?php
if (isset($_POST['tambah']))
{
	$nama = $_POST['nama'];
	$email = $_POST['email'];

	$table = "orang";
	$data = array
			(
				'email'=>$email, 
				'nama'=>$nama
			);
	$insert = $crud->masukkan($table, $data);
	//insert into orang (email, nama) values ('$email',$nama);
	echo "<br>";

	$show = $crud->tampil("*", $table)
				->join("left", "mhs","id_mhs","id")
				->selesai("select");
	print_r($show);
	echo "<br>";

	$d = $crud->hapus("a")
			  ->dimana("hapus","dimana","nama", "bukan", "1500")
			  ->dimana("hapus", "dan", "nama","bukan", "200")
			  ->selesai("delete"); 

	print_r($d);
	echo "<br>";

	$u = $crud->ubah("table", $data)
			  ->dimana("ubah","dimana","nama","sama","100")
			  ->selesai("update");
	print_r($u);
}