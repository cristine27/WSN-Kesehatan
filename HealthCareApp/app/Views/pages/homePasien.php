<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
</head>

<body>
    <div class="container">
        <div class="row">
            <h1 class="display-4">Health Care</h1>
            <div class="col">
                <div class="jumbotron jumbotron-fluid d-flex justify-content-center" style="background-color: beige;">
                    <p class="lead">Selamat datang <?= ($dataPasien['gender'] == "Pria") ? "Bapak " : "Ibu "; ?> <?= $dataPasien['nama']; ?></p>
                    <hr class="my-4">
                    <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="container">
                <!-- <div class="col"> -->
                <div class="col d-flex justify-content-center">
                    <a class="btn btn-primary btn-lg d-inline p-2" href="../Home/getHasilPantau" role="button">Hasil Pemantuan</a>
                    <a class="btn btn-primary btn-lg d-inline p-2" href="../Home/getPasienProfile" role="button">Profile</a>
                    <a class="btn btn-primary btn-lg d-inline p-2" href="/SignIn" role="button">Log out</a>
                </div>
            </div>
        </div>
    </div>

    <?= $this->endsection(); ?>