<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card w-75 mt-5">
                    <h4 class="card-header">Detail Pasien</h4>
                    <div class="card-body">
                        <h5 class="card-title"><b>Nama : </b><?= $pasien['nama']; ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><b>ID : </b><?= $pasien['idPasien']; ?></h6>
                        <br>
                        <p class="card-text"><b>Umur : </b> <?= $pasien['umur']; ?></p>
                        <p class="card-text"><b>Alamat : </b> <?= $pasien['alamat']; ?></p>
                        <p class="card-text"><b>Email : </b> <?= $pasien['email']; ?></p>
                        <p class="card-text"><b>Password : </b> <?= $pasien['password']; ?></p>

                        <a href="/Pasien/riwayatPasien/<?= $pasien['idPasien']; ?>" class="btn btn-info">Riwayat Periksa</a>
                        <a href="/Pasien/editPasien/<?= $pasien['idPasien']; ?>" class="btn btn-warning">Edit</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <a href="/Pasien">Kembali</a>
        </div>
    </div>
</body>
<?= $this->endsection(); ?>