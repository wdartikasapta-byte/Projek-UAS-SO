<?php
include "config/config.php";
$cari = "";

if(isset($_GET['cari'])){
    $cari = $_GET['cari'];

    $query = "SELECT * FROM tugas
              WHERE mata_kuliah LIKE '%$cari%'
              OR judul_tugas LIKE '%$cari%'
              ORDER BY deadline ASC";
}else{

$where = [];

$cari = "";

if(isset($_GET['cari']) && $_GET['cari'] != ""){

    $cari = $_GET['cari'];

    $where[] = "(mata_kuliah LIKE '%$cari%' OR judul_tugas LIKE '%$cari%')";

}

if(isset($_GET['status']) && $_GET['status'] != ""){

    $status = $_GET['status'];

    $where[] = "status='$status'";

}

$query = "SELECT * FROM tugas";

if(count($where) > 0){

    $query .= " WHERE ".implode(" AND ",$where);

}

$query .= " ORDER BY deadline ASC";

$result = mysqli_query($conn,$query);
}

$result = mysqli_query($conn,$query);
$total = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tugas"));
$belum = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tugas WHERE status='Belum Selesai'"));
$selesai = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tugas WHERE status='Selesai'"));
$hari_ini = date('Y-m-d');
$deadline = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tugas WHERE deadline='$hari_ini'"));

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TaskMahasiswa</title>

    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icon -->

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <nav class="navbar navbar-dark bg-primary shadow">

    <div class="container">

        <span class="navbar-brand fw-bold">

            📚 TaskMahasiswa

        </span>

        <span class="text-white">

            Halo, Mahasiswa 👋

        </span>

    </div>

</nav>
<div class="container mt-4">
    <div class="row mb-4">

     <div class="col">

        <a href="index.php" class="btn btn-primary">

            <i class="bi bi-house"></i>

            Dashboard

        </a>

        <a href="tambah.php" class="btn btn-success">

            <i class="bi bi-plus-circle"></i>

            Tambah Tugas

        </a>

        </div>

    </div>
    <div class="row g-3">
    
    <div class="col-md-3">

        <div class="card shadow bg-primary text-white">

            <div class="card-body">

                <h6>

                <i class="bi bi-list-task"></i>

                Total Tugas

                </h6>

                <h2><?= $total ?></h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card shadow bg-warning text-dark">

            <div class="card-body">

                <h6><i class="bi bi-hourglass"></i>
                Belum Selesai</h6>


                <h2><?= $belum ?></h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card shadow bg-success text-white">

            <div class="card-body">

                <h6><i class="bi bi-check-circle-fill"></i>
                Selesai</h6>

                <h2><?= $selesai ?></h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card shadow bg-danger text-white">

            <div class="card-body">

                <h6><i class="bi bi-alarm-fill"></i>
                Deadline Hari Ini</h6>

                <h2><?= $deadline ?></h2>

            </div>

        </div>

    </div>

    </div>
    <?php

$persentase = 0;

if($total > 0){

    $persentase = round(($selesai / $total) * 100);

}

?>

<div class="card shadow mt-4 mb-4">

    <div class="card-body">

        <h5>Progress Penyelesaian Tugas</h5>

        <div class="progress" style="height:25px;">

            <div
                class="progress-bar bg-success"
                role="progressbar"
                style="width: <?= $persentase ?>%;">

                <?= $persentase ?>%

            </div>

        </div>

    </div>

</div>
    <div class="card shadow mt-4">

    <div class="card-header bg-primary text-white">

        <h5 class="mb-0">

            Daftar Tugas

        </h5>

    </div>

    <div class="card-body">
    <form method="GET" class="mb-3">

    <div class="row">

        <div class="col-md-4">

            <select name="status" class="form-select">

                <option value="">Semua Status</option>

                <option value="Belum Selesai"
                <?= (isset($_GET['status']) && $_GET['status']=="Belum Selesai") ? "selected" : "" ?>>

                    Belum Selesai

                </option>

                <option value="Selesai"
                <?= (isset($_GET['status']) && $_GET['status']=="Selesai") ? "selected" : "" ?>>

                    Selesai

                </option>

            </select>

        </div>

        <div class="col-md-2">

            <button class="btn btn-primary w-100">

                Filter

            </button>

        </div>

    </div>

</form>
        <table class="table table-bordered table-hover">

            <thead>
                <div class="row mb-3">

<div class="col-md-6">

<form method="GET">

<div class="row mb-3">

<div class="col-md-10">

<input
type="text"
name="cari"
class="form-control"
placeholder="🔍 Cari mata kuliah atau judul tugas..."
value="<?= $cari ?>">

</div>

<div class="col-md-2">

<div class="col-md-2 d-flex gap-2">

<button
class="btn btn-primary flex-fill">

Cari

</button>

<a
href="index.php"
class="btn btn-secondary">

Reset

</a>

</div>

</div>

</div>

</form>

</div>

</div>

                <tr>

                    <th>No</th>

                    <th>Mata Kuliah</th>

                    <th>Judul</th>

                    <th>Deadline</th>

                    <th>Prioritas</th>

                    <th>Status</th>

                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

                <?php

                $no = 1;

                while($row = mysqli_fetch_assoc($result)){

                ?>

                <tr>

                    <td><?= $no++ ?></td>

                        <td><?= $row['mata_kuliah'] ?></td>

                            <td><?= $row['judul_tugas'] ?></td>

                    <td>

<?php

if($row['deadline'] < date("Y-m-d") && $row['status']=="Belum Selesai"){

echo "<span class='text-danger fw-bold'>".$row['deadline']."</span>";

}else{

echo $row['deadline'];

}

?>

</td>

                <td>

                <?php

if($row['prioritas']=="Tinggi"){

    echo "<span class='badge bg-danger'>Tinggi</span>";

}elseif($row['prioritas']=="Sedang"){

    echo "<span class='badge bg-warning text-dark'>Sedang</span>";

}else{

    echo "<span class='badge bg-success'>Rendah</span>";

}

?>

                </td>

                <td>

                <?php

if($row['status']=="Selesai"){

echo "<span class='badge bg-success'>Selesai</span>";

}else{

echo "<span class='badge bg-secondary'>Belum Selesai</span>";

}

?>

                </td>

            <td>

    <!-- Tombol Edit -->
    <a
        href="edit.php?id=<?= $row['id']; ?>"
        class="btn btn-warning btn-sm"
        title="Edit">

        <i class="bi bi-pencil-square"></i>

    </a>

    <!-- Tombol Hapus -->
    <a
        href="hapus.php?id=<?= $row['id']; ?>"
        class="btn btn-danger btn-sm"
        onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')"
        title="Hapus">

        <i class="bi bi-trash"></i>

    </a>

    <!-- Tombol Selesai -->
    <?php if($row['status']=="Belum Selesai"){ ?>

        <a
            href="selesai.php?id=<?= $row['id']; ?>"
            class="btn btn-success btn-sm"
            onclick="return confirm('Tandai tugas ini sebagai selesai?')"
            title="Selesai">

            <i class="bi bi-check-circle"></i>

        </a>

    <?php } else { ?>

        <button
            class="btn btn-secondary btn-sm"
            disabled
            title="Sudah selesai">

            <i class="bi bi-check-circle-fill"></i>

        </button>

    <?php } ?>

</td>
        </tr>

        <?php

}

            ?>

        </tbody>

        </table>

    </div>

</div>


</div> 

   


</body>

</html>