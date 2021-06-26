<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="text-center my-3">
                <a href="/Pasien/detail/<?= $pasien['idPasien']; ?>">Kembali</a>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <h1>Riwayat Pemeriksaan</h1>
            </div>
            <div class="col-4">
                <div class="container mt-5">
                    <form method="POST" action="/Pasien/riwayatPasien/<?= $pasien['idPasien']; ?>">
                        <div class="form-group">
                            <label for="filter">Filter Tanggal : </label>
                            <input type="date" id="filter" name="tanggal">
                            <button type="submit" class="btn btn-primary my-1">Cari</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="alert alert-info <?= ($flagFilter == false) ? 'show' : 'fade'; ?>" role="alert">
                Tidak ada pemeriksaan pada tanggal tersebut.
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p class="<?= ($flag == true) ? 'invisible' : 'visible'; ?>">Pasien Belum Melakukan Pemeriksaan</p>
                <?php
                $index = 0;
                foreach ($hasilPeriksa as $key => $value) { ?>
                    <h5 class="mt-3">Hasil Pemeriksaan : <?= $value['waktu']; ?></h5>
                    <div class="col d-flex justify-content-start">
                        <div class="w-75 table-responsive">
                            <table id="table_riwayat" class="table table-striped text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">Parameter</th>
                                        <th scope="col">Hasil</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <?php
                                for ($i = 0; $i < count($parameter[$index]); $i++) {
                                ?>
                                    <tbody>
                                        <tr>
                                            <th scope="row"><?= $parameter[$index][$i]['namaParameter']; ?></th>
                                            <td><?= $value['hasil' . strval($i + 1)]; ?></td>
                                            <td>
                                                <p class="text-<?= $status[$index][$i] == "normal" ? 'success' : 'danger'; ?>"><?= $status[$index][$i]; ?></p>
                                            </td>
                                        </tr>
                                    </tbody>
                                <?php }
                                $index++; ?>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>

<?= $this->endsection(); ?>