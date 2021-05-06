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
                        <h6 class="card-subtitle mb-2 text-muted"><b>Umur : </b><?= $pasien['umur']; ?></h6>
                        <br>
                        <p class="card-text"><b>Alamat : </b> <?= $pasien['alamat']; ?></p>
                        <p class="card-text"><b>Email : </b> <?= $pasien['email']; ?></p>
                        <p class="card-text"><b>Password : </b> <?= $pasien['password']; ?></p>

                        <a href="/Pasien/editPasien/<?= $pasien['idPasien']; ?>" class="btn btn-warning">Edit</a>
                        <form action="/Pasien/<?= $pasien['idPasien']; ?>" method="POST" class="d-inline">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="delete">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah anda yakin mau menghapus pasien?');">Hapus</button>
                        </form>
                    </div>
                </div>
                <div class="card w-75 mt-5">
                    <h4 class="card-header">Riwayat Pemeriksaan</h4>
                    <div class="card-body">
                        <p class="<?= ($flag == true) ? 'invisible' : 'visible'; ?>">Pasien Belum Melakukan Pemeriksaan</p>
                        <div class="col d-flex justify-content-start">
                            <div class="w-75 table-responsive">
                                <table class="table table-striped text-center">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col">Parameter</th>
                                            <th scope="col">Hasil</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $index = 0;
                                    // d($status);
                                    for ($i = 0; $i < count($parameter); $i++) { ?>
                                        <tbody>
                                            <tr>
                                                <th scope="row"><?= $parameter[$i]['namaParameter']; ?></th>
                                                <td><?= $hasilPeriksa[$index]['hasil' . strval($i + 1)]; ?></td>
                                                <td><?= $status[$i][0]; ?></td>
                                            </tr>
                                        </tbody>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <a href="/Pasien">Kembali</a>
        </div>
    </div>

    <?= $this->endsection(); ?>