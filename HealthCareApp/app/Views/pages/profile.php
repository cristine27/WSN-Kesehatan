<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="container-fluid h-75">
                    <?php d($dataPasien); ?>
                    <h1 class="display-4">Profile Pasien</h1>
                    <br><br>

                    <div class="col-md-8">
                        <div class="card mb-3" style="background-color: bdd2b6;">
                            <div class="card-body">
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
                    <summary>Ubah Password ?</summary>
                    <div class="card">
                        <div class="card-header">
                            Ganti Password
                        </div>
                        <div class="card-body">

                        </div>
                    </div>
                </details>
                <form action="/Home/gantiPass" method="POST">
                    <div class="form-group">
                        <label for="newPass">Password</label>
                        <input type="text" name="newPass" class="form-control <?= ($validation->hasError('pass')) ? 'is-invalid' : ''; ?>" placeholder="Masukkan password baru anda." required autofocus>
                        <div class="invalid-feedback">
                            <?= $validation->getError('pass'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <a class="btn btn-info w-25" href="/Home">Kembali</a>
        </div>
    </div>

    <?= $this->endsection(); ?>