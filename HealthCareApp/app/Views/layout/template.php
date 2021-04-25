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
    <title><?= $title; ?></title>

    <!-- my js -->
    <script src="../js/script.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="background-image: url('../img/bg form.jpg')">
        <!-- <div class="container">
            <div class="row">
                <div class="col"> -->
        <a class="navbar-brand mx-2" href="#">Health Care</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>

        </button>


        <!-- <img src="../img/unpar.png" alt="logo" width="50" height="38"> -->
        <div class="collapse navbar-collapse mt-3" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link active" aria-current="page" href="/">Home</a>
                <a class="nav-link" href="/pages/signin">sign in</a>
                <a class="nav-link" href="/pages/profile">profile</a>
                <a class="nav-link" href="/pages/addPasien">add Pasien</a>
                <a class="nav-link" href="/Pasien">list Pasien</a>
            </div>
        </div>

        <div class="row mx-2">
            <div class="col">
                <img src="../img/profile.png" alt="profile icon" width="50" height="50">
            </div>

        </div>
    </nav>

    <?= $this->renderSection('content'); ?>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>
    -->

    <!-- <footer class=" container-fluid page-footer font-small blue mt-4 bg-dark" style="background-image: url('../img/bg form.jpg')"> -->
    <!-- Copyright -->
    <!-- <div class="footer-copyright text-center py-3">© 2020 Copyright: -->
    <!-- <a href="https://mdbootstrap.com/"> Words LBW</a> -->
    <!-- </div> -->
    <!-- Copyright -->
    <!-- </footer> -->
    <!-- <script src="../assets/dist/js/bootstrap.bundle.min.js"></script> -->
</body>

</html>