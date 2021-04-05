<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

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
                                <form method="GET" action="#">
                                    <?= csrf_field(); ?>

                                    <!-- <h1 class="h3 mb-3 fw-normal">Please sign in</h1> -->
                                    <label for="inputEmail" class="visually-hidden">Email address</label>
                                    <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
                                    <label for="inputPassword" class="visually-hidden">Password</label>
                                    <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
                                    <div class="checkbox mb-3">
                                        <label>
                                            <input type="checkbox" value="remember-me"> Remember me
                                        </label>
                                    </div>
                                    <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
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

<?= $this->endsection(); ?>