<?php
if (userLogin()['level'] == 3) {
  header("location:" . $main_url . "error-page.php");
  exit();
}

function insert($data)
{
  global $koneksi;

  $nama = mysqli_real_escape_string($koneksi, $data['nama']);
  $telpon = mysqli_real_escape_string($koneksi, $data['telpon']);
  $ketr = mysqli_real_escape_string($koneksi, $data['ketr']);
  $alamat = mysqli_real_escape_string($koneksi, $data['alamat']);

  // variable 
  $sqlSupplier = "INSERT INTO tbl_supplier VALUES (null, '$nama', '$telpon', '$ketr', '$alamat')";
  mysqli_query($koneksi, $sqlSupplier);
  return mysqli_affected_rows($koneksi);
}

function delete($id)
{
  global $koneksi;

  $sqlDelete = "DELETE FROM tbl_supplier WHERE id_supplier = $id";
  mysqli_query($koneksi, $sqlDelete);

  return mysqli_affected_rows($koneksi);
}

function update($data)
{
  global $koneksi;

  $id = mysqli_real_escape_string($koneksi, $data['id']);
  $nama = mysqli_real_escape_string($koneksi, $data['nama']);
  $telpon = mysqli_real_escape_string($koneksi, $data['telpon']);
  $ketr = mysqli_real_escape_string($koneksi, $data['ketr']);
  $alamat = mysqli_real_escape_string($koneksi, $data['alamat']);

  // variable untuk update data
  $sqlSupplier = "UPDATE tbl_supplier SET nama = '$nama', telp = '$telpon', deskripsi = '$ketr', alamat = '$alamat' WHERE id_supplier = '$id' ";
  mysqli_query($koneksi, $sqlSupplier);
  return mysqli_affected_rows($koneksi);
}
