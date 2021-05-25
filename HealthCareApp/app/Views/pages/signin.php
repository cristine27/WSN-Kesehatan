<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="display-4">WELCOME !</h1>
                <hr class="my-4">
                <div class="container-fluid w-75 h-50 p-3">
                    <div class="row  d-flex justify-content-center text-center" style="background-image: url('../img/bg form.jpg')">
                        <div class="col">
                            <img class="rounded-circle .mt-n2" src="../img/unpar.png" alt="Health Care Logo" width="150" height="150">
                            <!-- <div class="jumbotron jumbotron-fluid">

                            </div>  w-50 p-3-->
                            <div class="container">

                                <form method="POST" action="/SignIn/validateLogin">
                                    <?= csrf_field(); ?>

                                    <!-- <h1 class="h3 mb-3 fw-normal">Please sign in</h1> -->
                                    <label for="email" class="visually-hidden">Email</label>
                                    <input type="email" id="email" name="email" class="form-control <?= ($validate == 'false') ? 'is-invalid' : ''; ?>" placeholder="Email address" required autofocus>
                                    <br>
                                    <label for="password" class="visually-hidden">Password</label>
                                    <input type="password" id="password" name="password" class="form-control <?= ($validate == 'false') ? 'is-invalid' : ''; ?>" placeholder="Password" required>
                                    <br>
                                    <div class="invalid-feedback">
                                        <b><?= $pesan; ?></b>
                                    </div>
                                    <br>
                                    <button class="btn btn-info" value="Submit">Sign In</button>
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