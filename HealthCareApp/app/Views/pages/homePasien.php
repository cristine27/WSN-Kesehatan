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
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="jumbotron">
                    <h1 class="display-4">Health Care</h1>
                    <p class="lead">Selamat datang <?= ($dataPasien['gender'] == "Pria") ? "Bapak " : "Ibu "; ?> <?= $dataPasien['nama']; ?></p>
                    <hr class="my-4">
                    <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
                    <p class="lead">
                        <a class="btn btn-primary btn-lg" href="SignIn/getHasilPantau/<?= $dataPasien['idPasien']; ?>" role="button">Hasil Pemantuan</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>