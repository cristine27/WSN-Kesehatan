<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
</head>

<body style="background-image: url('../img/bg hallway1.jpg');">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="container-fluid h-75">
                    <h1 class="display-4">Profile Pasien</h1>
                    <br><br>
                    <div class="col-md-8">
                        <div class="card mb-3" style="background-color: bdd2b6;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">ID</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <?= $dataPasien['idPasien']; ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Nama</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <?= $dataPasien['nama']; ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Email</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <?= $dataPasien['email']; ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Gender</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <?= $dataPasien['gender']; ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Umur</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <?= $dataPasien['umur']; ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Alamat</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <?= $dataPasien['alamat']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <details>
                    <summary><b>Ubah Password ?</b></summary>
                    <div class="card">
                        <div class="card-header">
                            <b>Ganti Password</b>
                        </div>
                        <div class="card-body" style="background-color: bisque;">
                            <form action="/Home/gantiPass" method="POST">
                                <div class="form-group">
                                    <input type="text" name="newPass" class="form-control <?= ($validation->hasError('newPass')) ? 'is-invalid' : ''; ?>" placeholder="Masukkan password baru anda." required autofocus>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('newPass'); ?>
                                    </div>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <br>
                            </form>
                        </div>
                    </div>
                </details>

            </div>
        </div>

        <div class="row">
            <a class="btn btn-info w-25" href="/Home">Kembali</a>
        </div>
    </div>
</body>
<?= $this->endsection(); ?>