<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

</head>

<body onload="showModal(<?php echo $flag ?>)">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="container">
                    <div class="row">
                        <div class="card w-90 mb-3 text-center">
                            <?php if (session()->getFlashdata('pesan')) : ?>
                                <div class="alert alert-success" role="alert">
                                    <?= session()->getFlashdata('pesan'); ?>
                                </div>
                            <?php endif; ?>
                            <h4 class="card-header" style="background-color: ffd3b4;">Health Care App</h4>
                            <div class="card-body">
                                <p class="card-text">
                                    Health Care App adalah sebuah aplikasi yang digunakan untuk melihat hasil Pemeriksaan kesehatan yang telah dilakukan oleh pasien.
                                    Pemeriksaan kesehatan terdiri dari pemeriksaan detak jantung, saturasi oksigen, serta suhu tubuh.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card w-90 text-center">
                            <h3 class="card-header" style="background-color: ffd3b4;">Selamat Datang <?= ($dataPasien['gender'] == "Pria") ? "Bapak " : "Ibu "; ?> <?= $dataPasien['nama']; ?></h3>
                            <div class="card-body">
                                <p class="card-text">
                                    Silahkan Memilih Fitur yang Ingin di Jalankan :
                                </p>
                                <div class="container">
                                    <!-- <div class="col"> -->
                                    <div class="d-flex justify-content-center">
                                        <a class="btn btn-primary btn-lg d-inline p-2 mx-3" href="../Home/getHasilPantau" role="button">Hasil Pemantuan</a>
                                        <a class="btn btn-primary btn-lg d-inline p-2 mx-3" href="../Home/getPasienProfile" role="button">Profile</a>
                                        <a class="btn btn-primary btn-lg d-inline p-2 mx-3" href="/SignIn" role="button">Log out</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div onload="showModal($flag)" class="modal" id="myModal" data-bs-backdrop="static" aria-hidden="true" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel"><b>Perhatian!!</b></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Demi keamanan akun mohon segera menganti password default akun anda.<br>
                                <a href="../Home/getPasienProfile">Ganti password sekarang.</a>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript">
    function showModal(x) {
        if (x) {
            $('#myModal').modal('show');
        } else {
            $('#myModal').modal('hide');
        }
    }
</script>
<?= $this->endsection(); ?>