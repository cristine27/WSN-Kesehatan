<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card mt-5" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title"><b>Nama : </b><?= $pasien['nama']; ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><b>Umur : </b><?= $pasien['umur']; ?></h6>
                        <br><br>
                        <p class="card-text"><b>Alamat : </b> <?= $pasien['alamat']; ?></p>
                        <p class="card-text"><b>Email : </b> <?= $pasien['email']; ?></p>
                        <p class="card-text"><b>Password : </b> <?= $pasien['password']; ?></p>

                        <a href="/Pasien/editPasien/<?= $pasien['idPasien']; ?>" class="btn btn-warning">Edit</a>
                        <form action="/Pasien/<?= $pasien['idPasien']; ?>" method="POST" class="d-inline">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="delete">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah anda yakin mau menghapus pasien?');">Hapus</button>
                        </form>

                        <br><br>
                        <a href="/Pasien">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= $this->endsection(); ?>