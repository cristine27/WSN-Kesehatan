<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <!-- my css -->
    <link href="stylesheet" href="../css/style.css">
    <title>Hello, world!</title>

    <!-- my js -->
    <script src="../js/script.js"></script>
</head>

<body>
    <div class="container-fluid" style="background-color: #edffec;">
        <div class="row">
            <div class="col">
                <h1 class="display-4">WELCOME !</h1>
                <hr class="my-4">
                <div class="container-fluid w-50 p-3">
                    <div class="row  d-flex justify-content-center text-center" style="background-image: url('../img/bg form.jpg')">
                        <div class="col">
                            <img class="rounded-circle .mt-n2" src="../img/unpar.png" alt="Health Care Logo" width="150" height="150">
                            <div class="jumbotron jumbotron-fluid">
                                <div class="container w-50 p-3">

                                    <form method="POST" action="/SignIn/validateLogin">
                                        <?= csrf_field(); ?>

                                        <!-- <h1 class="h3 mb-3 fw-normal">Please sign in</h1> -->
                                        <label for="email" class="visually-hidden">Email</label>
                                        <input type="email" id="email" name="email" class="form-control" placeholder="Email address" required autofocus>

                                        <label for="password" class="visually-hidden">Password</label>
                                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>

                                        <div class="checkbox mb-3">
                                            <label>
                                                <input type="checkbox" id="remember"> Remember me
                                            </label>
                                        </div>
                                        <p display="<?= ($validate == 'false') ? 'block' : 'none'; ?>"><?= $pesan; ?></p>
                                        <button class="btn btn-info" value="Submit">Sign In</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row d-flex">
            <img src="../img/bg.png" alt="">
        </div>
    </div>
</body>

</html>