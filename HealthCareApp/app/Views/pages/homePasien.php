<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="container">

                    <div class="row">
                        <div class="card w-90 mb-3 text-center">
                            <h4 class="card-header">Health Care App</h4>
                            <div class="card-body">
                                <p class="card-text">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur optio quae numquam,
                                    ipsam nulla accusamus ullam cumque quibusdam illum voluptates nostrum doloremque rerum
                                    tempora? Maxime pariatur provident vitae dolorem non.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card w-90 text-center">
                            <h3 class="card-header">Header</h3>
                            <div class="card-body">
                                <p class="card-text">
                                    Silahkan Pilih Opsi
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
                    <div class="jumbotron jumbotron-fluid h-75" style="background-color: beige;">
                        <p class="lead">Selamat datang <?= ($dataPasien['gender'] == "Pria") ? "Bapak " : "Ibu "; ?> <?= $dataPasien['nama']; ?></p>
                        <hr class="my-4">
                        <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
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
    </div>

    <?= $this->endsection(); ?>