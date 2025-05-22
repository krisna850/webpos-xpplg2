<?php
if (userLogin()['level'] != 1) {
    header("location:" . $main_url . "error-page.php");
    exit();
}

function generateId()
{
    global $koneksi;

    $queryId = mysqli_query($koneksi, "SELECT MAX(id_barang) AS maxid FROM tbl_barang");
    $data = mysqli_fetch_array($queryId);

    $maxid = $data['maxid'] ?? 'BRG-000'; // Default jika null
    $noUrut = (int) substr($maxid, 4, 3); // Ambil angka dari BRG-001
    $noUrut++;
    $maxid = "BRG-" . sprintf("%03s", $noUrut); // Format ID

    return $maxid;
}

function insert($data)
{
    global $koneksi;

    $id = strtolower(mysqli_real_escape_string($koneksi, $data['kode']));
    $barcode = mysqli_real_escape_string($koneksi, $data['barcode']);
    $name = mysqli_real_escape_string($koneksi, $data['nama_barang']);
    $satuan = mysqli_real_escape_string($koneksi, $data['satuan']);
    $harga_beli = mysqli_real_escape_string($koneksi, $data['harga_beli']);
    $harga_jual = mysqli_real_escape_string($koneksi, $data['harga_jual']);
    $stockmin = mysqli_real_escape_string($koneksi, $data['stock_minimal']);
    $gambar = mysqli_real_escape_string($koneksi, $_FILES['image']['name']);

    // Cek barcode duplikat
    $cekBarcode = mysqli_query($koneksi, "SELECT * FROM tbl_barang WHERE barcode = '$barcode'");
    if (mysqli_num_rows($cekBarcode) > 0) {
        echo "<script>alert('Kode barcode sudah ada, barang gagal ditambahkan')</script>";
        return false;
    }

    // Upload gambar
    if (!empty($gambar)) {
        $gambar = uploadimg(null, $id);
        if ($gambar == '') {
            return false;
        }
    } else {
        $gambar = 'default.jpg';
    }

    $sqlBarang = "INSERT INTO tbl_barang VALUES ('$id', '$barcode', '$name', '$harga_beli', '$harga_jual', 0, '$satuan', '$stockmin', '$gambar')";
    mysqli_query($koneksi, $sqlBarang);
    return mysqli_affected_rows($koneksi);
}

function delete($id, $gbr)
{
    global $koneksi;

    // Delete data dari database dengan prepared statement untuk mencegah SQL Injection
    $stmt = $koneksi->prepare("DELETE FROM tbl_barang WHERE id_barang = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();

    // Cek apakah gambar bukan 'download.jpg' dan valid
    if ($gbr !== 'download.jpg' && preg_match('/^[\w\-]+\.(jpg|jpeg|png|gif)$/i', $gbr)) {
        // Dapatkan path file gambar
        $path = realpath(__DIR__ . '/../assets/image/' . $gbr);

        // Cek apakah path valid dan file ada
        if ($path && file_exists($path)) {
            unlink($path);  // Hapus file
        } else {
            echo "File tidak ditemukan: $path";
        }
    }

    return $stmt->affected_rows;
}

// Fungsi select user level
function selectUser1($level)
{
    return ($level == 1) ? "selected" : null;
}

function selectUser2($level)
{
    return ($level == 2) ? "selected" : null;
}

function selectUser3($level)
{
    return ($level == 3) ? "selected" : null;
}

function update($data)
{
    global $koneksi;

    $iduser = mysqli_real_escape_string($koneksi, $data['id']);
    $username = strtolower(mysqli_real_escape_string($koneksi, $data['username']));
    $fullname = mysqli_real_escape_string($koneksi, $data['fullname']);
    $level = mysqli_real_escape_string($koneksi, $data['level']);
    $address = mysqli_real_escape_string($koneksi, $data['address']);
    $gambar = mysqli_real_escape_string($koneksi, $_FILES['image']['name']);
    $fotoLama = mysqli_real_escape_string($koneksi, $data['oldImg']);

    // Cek username sekarang
    $queryUsername = mysqli_query($koneksi, "SELECT * FROM tbl_user WHERE userid = '$iduser'");
    $dataUsername = mysqli_fetch_assoc($queryUsername);
    $curUsername = $dataUsername['username'];

    // Cek username baru jika berubah
    if ($username !== $curUsername) {
        $newUsername = mysqli_query($koneksi, "SELECT username FROM tbl_user WHERE username = '$username'");
        if (mysqli_num_rows($newUsername)) {
            echo "<script>alert('Username sudah terpakai, update data user gagal !'); document.location.href = 'data-user.php';</script>";
            return false;
        }
    }

    // Upload gambar jika ada
    if (!empty($gambar)) {
        $imgUser = uploadimg("data-user.php");
        if ($fotoLama != 'default.png') {
            @unlink('../assets/image/' . $fotoLama);
        }
    } else {
        $imgUser = $fotoLama;
    }

    mysqli_query($koneksi, "UPDATE tbl_user SET username = '$username', fullname = '$fullname', address = '$address', level = '$level', foto = '$imgUser' WHERE userid = '$iduser'");

    return mysqli_affected_rows($koneksi);
}
