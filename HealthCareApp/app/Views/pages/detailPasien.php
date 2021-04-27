<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="card mt-5" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"><b>Nama : </b><?= $pasien['nama']; ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><b>Umur : </b><?= $pasien['umur']; ?></h6>
                    <!-- test parse data -->
                    <p class="card-text"><b>Alamat : </b> <?= $pasien['gender']; ?></p>
                    <a href="#" class="btn btn-warning">Edit</a>
                    <a href="/Pasien/deletePasien/<?= $pasien['idPasien']; ?>" class="btn btn-danger">Hapus</a>
                    <br><br>
                    <a href="/Pasien">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endsection(); ?>