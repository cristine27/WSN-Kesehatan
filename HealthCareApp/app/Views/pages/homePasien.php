<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid" style="background-color: #edffec;">
    <div class=" row">
        <div class="col">
            <div class="container">
                <div class="row">
                    <h5 class="mt-5">Hasil Pemeriksaan : <?= $hasilPeriksa['waktu']; ?></h5>
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

                                <tbody>
                                    <tr>
                                        <th scope="row">Detak Jantung</th>
                                        <td><?= $hasilPeriksa['hasil1']; ?></td>
                                        <td>normal</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Saturasi Oksigen</th>
                                        <td><?= $hasilPeriksa['hasil1']; ?></td>
                                        <td>normal</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Suhu Tubuh</th>
                                        <td><?= $hasilPeriksa['hasil1']; ?></td>
                                        <td class="table-danger">abnormal</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col d-flex justify-content-end">
                        <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title">Keterangan : </h5>
                                <p class="card-text">
                                    Detak jantung = 70 bpm<br>
                                    Saturasi oksigen = 90%<br>
                                    Suhu = 37<span>&#8451;</span><br>
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
        </div>
    </div>

</div>

<?= $this->endsection(); ?>