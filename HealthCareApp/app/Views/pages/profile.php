<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="jumbotron jumbotron-fluid">
                <div class="container">
                    <h1 class="display-4">Profile Pasien</h1>
                    <!-- <p class="lead">This is a modified jumbotron that occupies the entire horizontal space of its parent.</p> -->
                    <br><br>
                    <p>Nama : <?= $dataPasien['nama']; ?></p>
                    <br>
                    <P>Alamat : <?= $dataPasien['alamat']; ?></P>
                    <br>
                    <P>Jenis Kelamin : <?= $dataPasien['gender']; ?></P>
                    <br>
                    <P>Umur : <?= $dataPasien['umur']; ?></P>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endsection(); ?>