<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<meta http-equiv="refresh" content="8">
</head>

<body>
    <div class="container">
        <div class=" row">
            <div class="col">
                <div class="container">
                    <div class="row">
                        <?php for ($index = 0; $index < count($hasilPeriksa); $index++) { ?>
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
                                        $index = 0;
                                        for ($i = 0; $i < count($parameter); $i++) { ?>
                                            <tbody>
                                                <tr>
                                                    <th scope="row"><?= $parameter[$i]['namaParameter']; ?></th>
                                                    <td><?= $hasilPeriksa[$index]['hasil' . strval($i + 1)]; ?></td>
                                                    <td>
                                                        <p class="text-<?= $status[$i] == "normal" ? 'success' : 'danger'; ?>"><?= $status[$i]; ?></p>
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
                    <a href="/Home">Kembali</a>
                </div>
            </div>
        </div>
</body>
<?= $this->endsection(); ?>