<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<meta http-equiv="refresh" content="4">
</head>

<body>
    <div class="container">
        <div class=" row">
            <div class="col">
                <div class="container">
                    <div class="row">
                        <?php for ($index = 0; $index < count($node1); $index++) { ?>
                            <h5>Pasien : <?= $pasien1[$index]['nama']; ?></h5>
                            <h5 class="mt-5">Hasil Pemeriksaan : <?= $node1[$index]['waktu']; ?></h5>
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
                                        $indexHasil = 0;

                                        for ($i = 0; $i < count($param1); $i++) {
                                            if ($param1[$i]['namaParameter'] == "Detak jantung") {
                                                $indexHasil = 1;
                                            } else if ($parame1[$i]['namaParameter'] == "Saturasi Oksigen") {
                                                $indexHasil = 2;
                                            } else if ($param1[$i]['namaParameter'] == "Temperatur") {
                                                $indexHasil = 3;
                                            }
                                        ?>
                                            <tbody>
                                                <tr>
                                                    <th scope="row"><?= $param1[$i]['namaParameter']; ?></th>
                                                    <td><?= $node1[$index]['hasil' . strval($indexHasil)]; ?></td>
                                                    <td>
                                                        <p class="text-<?= $status1[$i] == "normal" ? 'success' : 'danger'; ?>"><?= $status1[$i]; ?></p>
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
                <div class="container">
                    <div class="row">
                        <?php for ($index = 0; $index < count($node2); $index++) { ?>
                            <h5>Pasien : <?= $pasien2[$index]['nama']; ?></h5>
                            <h5 class="mt-5">Hasil Pemeriksaan : <?= $node2[$index]['waktu']; ?></h5>
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
                                        $indexHasil = 0;

                                        for ($i = 0; $i < count($param2); $i++) {
                                            if ($param2[$i]['namaParameter'] == "Detak jantung") {
                                                $indexHasil = 1;
                                            } else if ($param2[$i]['namaParameter'] == "Saturasi Oksigen") {
                                                $indexHasil = 2;
                                            } else if ($param2[$i]['namaParameter'] == "Temperatur") {
                                                $indexHasil = 3;
                                            }
                                        ?>
                                            <tbody>
                                                <tr>
                                                    <th scope="row"><?= $param2[$i]['namaParameter']; ?></th>
                                                    <td><?= $node2[$index]['hasil' . strval($indexHasil)]; ?></td>
                                                    <td>
                                                        <p class="text-<?= $status2[$i] == "normal" ? 'success' : 'danger'; ?>"><?= $status2[$i]; ?></p>
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
                <div class="container">
                    <div class="row">
                        <div class="col d-flex justify-content-end">
                            <div class="card" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">Keterangan : </h5>
                                    <p class="card-text">
                                        Saturasi oksigen = 95% - 100%<br>
                                        Suhu = 36<span>&#8451;</span> - 37<span>&#8451;</span><br>
                                        Tekanan darah = 120/70<br>
                                    </p>
                                </div>
                            </div>
                            <!-- <div class="alert alert-secondary w-25" role="alert">
                            <label>Keterangan batas normal : </label>
                            <p>
                                Detak jantung = 70 bpm<br>
                                Saturasi oksigen = 90%<br>
                                Suhu = 37<span>&#8451;</span><br>
                                Tekanan darah = 120/70<br>
                            </p>
                        </div> -->
                        </div>
                    </div>
                </div>
                <div class="container">
                    <a href="/Pasien">Kembali</a>
                </div>
            </div>
        </div>
</body>
<?= $this->endsection(); ?>