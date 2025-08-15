<?php
// ==== CLASS ====
class Mahasiswa {
    public $nim;
    public $nama;
    public $jurusan;

    public function __construct($nim, $nama, $jurusan) {
        $this->nim = $nim;
        $this->nama = $nama;
        $this->jurusan = $jurusan;
    }
}

class DataMahasiswa {
    private $list = [];

    public function __construct() {
        // Data awal
        $this->list = [
            new Mahasiswa("202101", "Andi", "Informatika"),
            new Mahasiswa("202102", "Budi", "Sistem Informasi"),
            new Mahasiswa("202103", "Citra", "Teknik Komputer")
        ];
    }

    // Ambil semua data
    public function getAll() {
        return $this->list;
    }

    // Tambah data baru
    public function tambah(Mahasiswa $mhs) {
        $this->list[] = $mhs;
    }

    // Hapus data berdasarkan index
    public function hapus($index) {
        if (isset($this->list[$index])) {
            array_splice($this->list, $index, 1);
        }
    }

    // Edit data
    public function edit($index, Mahasiswa $mhs) {
        if (isset($this->list[$index])) {
            $this->list[$index] = $mhs;
        }
    }
}

// ==== LOGIKA APLIKASI ====
// Simpan data di session supaya bertahan saat refresh halaman
session_start();
if (!isset($_SESSION['data'])) {
    $_SESSION['data'] = serialize(new DataMahasiswa());
}

$dataMahasiswa = unserialize($_SESSION['data']);

// Tambah data
if (isset($_POST['tambah'])) {
    $mhs = new Mahasiswa($_POST['nim'], $_POST['nama'], $_POST['jurusan']);
    $dataMahasiswa->tambah($mhs);
}

// Edit data
if (isset($_POST['update'])) {
    $index = $_POST['index'];
    $mhs = new Mahasiswa($_POST['nim'], $_POST['nama'], $_POST['jurusan']);
    $dataMahasiswa->edit($index, $mhs);
}

// Hapus data
if (isset($_GET['hapus'])) {
    $dataMahasiswa->hapus($_GET['hapus']);
}

// Simpan kembali ke session
$_SESSION['data'] = serialize($dataMahasiswa);

// Jika sedang mode edit
$editData = null;
$editIndex = null;
if (isset($_GET['edit'])) {
    $editIndex = $_GET['edit'];
    $all = $dataMahasiswa->getAll();
    if (isset($all[$editIndex])) {
        $editData = $all[$editIndex];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Data Mahasiswa (OOP)</title>
    <style>
        table { border-collapse: collapse; width: 60%; margin-bottom: 20px; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: left; }
        th { background-color: #ddd; }
    </style>
</head>
<body>
    <h2>Daftar Mahasiswa</h2>
    <table>
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Jurusan</th>
            <th>Aksi</th>
        </tr>
        <?php
        $no = 1;
        foreach ($dataMahasiswa->getAll() as $index => $mhs) {
            echo "<tr>
                <td>{$no}</td>
                <td>{$mhs->nim}</td>
                <td>{$mhs->nama}</td>
                <td>{$mhs->jurusan}</td>
                <td>
                    <a href='?edit={$index}'>Edit</a> | 
                    <a href='?hapus={$index}' onclick=\"return confirm('Hapus data ini?')\">Hapus</a>
                </td>
            </tr>";
            $no++;
        }
        ?>
    </table>

    <h2><?php echo $editData ? "Edit Data" : "Tambah Data"; ?></h2>
    <form method="post">
        <input type="hidden" name="index" value="<?php echo $editIndex; ?>">
        <label>NIM:</label><br>
        <input type="text" name="nim" value="<?php echo $editData ? $editData->nim : ''; ?>" required><br><br>
        
        <label>Nama:</label><br>
        <input type="text" name="nama" value="<?php echo $editData ? $editData->nama : ''; ?>" required><br><br>
        
        <label>Jurusan:</label><br>
        <input type="text" name="jurusan" value="<?php echo $editData ? $editData->jurusan : ''; ?>" required><br><br>
        
        <?php if ($editData): ?>
            <button type="submit" name="update">Update</button>
        <?php else: ?>
            <button type="submit" name="tambah">Tambah</button>
        <?php endif; ?>
    </form>
</body>
</html>
