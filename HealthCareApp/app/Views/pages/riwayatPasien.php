<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
</head>

<body>
    <div class="container">
        <div class="row">
            <a href="/Pasien/detail/<?= $pasien['idPasien']; ?>">Kembali</a>
        </div>
        <div class="row">
            <div class="col">
                <div class="card w-75 mt-5">

                    <h4 class="card-header">Riwayat Pemeriksaan</h4>

                    <div class="card-body">
                        <p class="<?= ($flag == true) ? 'invisible' : 'visible'; ?>">Pasien Belum Melakukan Pemeriksaan</p>
                        <?php for ($index = 0; $index < $jumlahHasil; $index++) { ?>
                            <h5 class="mt-5">Hasil Pemeriksaan : <?= $hasilPeriksa[$index]['waktu']; ?></h5>
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
                                        for ($i = 0; $i < 3; $i++) { ?>
                                            <tbody>
                                                <tr>
                                                    <th scope="row"><?= $parameter[$index][$i]['namaParameter']; ?></th>
                                                    <td><?= $hasilPeriksa[$index]['hasil' . strval($i + 1)]; ?></td>
                                                    <td>
                                                        <p class="badge badge-<?= $status[$index][$i] == "normal" ? 'success' : 'danger'; ?>"><?= $status[$index][$i]; ?></p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        <?php } ?>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= $this->endsection(); ?>