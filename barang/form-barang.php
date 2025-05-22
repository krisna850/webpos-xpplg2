<?php
session_start();
if (!isset($_SESSION['ssLoginPOS'])) {
  header("location: ../auth/login.php");
  exit();
}

require '../config/config.php';
require '../config/functions.php';
require '../module/mode-barang.php';

$title = 'Tambah Barang | Market PPLG';
require '../template/header.php';
require '../template/navbar.php';
require '../template/sidebar.php';

$alert = ''; // Definisikan default alert

if (isset($_POST['simpan'])) {
  if (insert($_POST) > 0) {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="icon fas fa-check"></i> Barang berhasil ditambahkan.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>';
  }
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Barang</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= $main_url ?>barang">Barang</a></li> 
            <li class="breadcrumb-item active">Add Barang</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <?php if ($alert != '') echo $alert; ?>
      <div class="card">
        <form action="" method="post" enctype="multipart/form-data">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-plus fa-sm"></i> Add Barang</h3>
            <button type="submit" name="simpan" class="btn btn-primary btn-sm float-right">
              <i class="fas fa-save"></i> Simpan
            </button>
            <button type="reset" class="btn btn-danger btn-sm float-right mr-1">
              <i class="fas fa-times"></i> Reset
            </button>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-lg-8 mb-3">
                <div class="form-group">
                  <label for="kode">Kode</label>
                  <input type="text" name="kode" id="kode" class="form-control" value="<?= generateId() ?>" readonly>
                </div>
                <div class="form-group">
                  <label for="barcode">Barcode *</label>
                  <input type="text" name="barcode" id="barcode" class="form-control" placeholder="barcode" autofocus autocomplete="off" required>
                </div>
                <div class="form-group">
                  <label for="nama_barang">Nama Barang *</label>
                  <input type="text" name="nama_barang" id="nama_barang" class="form-control" placeholder="Nama Barang" autocomplete="off" required>
                </div>
                <div class="form-group">
                  <label for="satuan">Satuan *</label>
                  <select name="satuan" id="satuan" class="form-control" required>
                    <option value="">-- Satuan Barang --</option>
                    <option value="Piece">Piece</option>
                    <option value="Botol">Botol</option>
                    <option value="Kaleng">Kaleng</option>
                    <option value="Pouch">Pouch</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="harga_beli">Harga Beli *</label>
                  <input type="number" name="harga_beli" id="harga_beli" class="form-control" placeholder="Rp.0" autocomplete="off" required>
                </div>
                <div class="form-group">
                  <label for="harga_jual">Harga Jual *</label>
                  <input type="number" name="harga_jual" id="harga_jual" class="form-control" placeholder="Rp.0" required>
                </div>
                <div class="form-group">
                  <label for="stock_minimal">Stock Minimal *</label>
                  <input type="number" name="stock_minimal" id="stock_minimal" class="form-control" placeholder="0" autocomplete="off" required>
                </div>
              </div>
              <div class="col-lg-4 text-center">
                <img src="<?= $main_url ?>assets/image/download.jpg" class="profile-user-img img-circle mb-3" alt="User">
                <input type="file" name="image" class="form-control">
                <span class="text-sm">Type file gambar JPG | PNG | GIF</span><br>
                <span class="text-sm">Width = Height</span>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>
<?php require '../template/footer.php'; ?>