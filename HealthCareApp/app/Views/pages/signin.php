<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
</head>

<body style="background-image: url('../img/bg hallway.jpg');">
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="display-3">Health Care App</h1>
                <hr class="my-4">
                <div class="container-fluid w-75 h-50 p-3">
                    <div class="row  d-flex justify-content-center text-center">
                        <div class="col">
                            <img class="rounded-circle .mt-n2" src="../img/unpar.png" alt="Logo UNPAR" width="150" height="150">

                            <div class="container w-50 my-3">
                                <form method="POST" action="/SignIn/validateLogin">
                                    <?= csrf_field(); ?>
                                    <!-- <h1 class="h3 mb-3 fw-normal">Please sign in</h1> -->
                                    <label for="email" class="fa fa-envelope prefix grey-text">Email</label>
                                    <input type="email" id="email" name="email" class="form-control <?= ($validate == 'false') ? 'is-invalid' : ''; ?>" placeholder="Email address" required autofocus>

                                    <label for="password" class="fa fa-lock prefix grey-text">Password</label>
                                    <input type="password" id="password" name="password" class="form-control <?= ($validate == 'false') ? 'is-invalid' : ''; ?>" placeholder="Password" required>

                                    <div class="invalid-feedback my-1">
                                        <b><?= $pesan; ?></b>
                                    </div>

                                    <button class="btn btn-info my-2" value="Submit">Sign In</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="row d-flex">
            <img src="../img/bg.png" alt="">
        </div> -->
    </div>
</body>
<?= $this->endsection(); ?>