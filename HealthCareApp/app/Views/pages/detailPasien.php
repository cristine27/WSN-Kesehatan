<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"><b>Nama : </b><?= $pasien['nama']; ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><b>Umur : </b><?= $pasien['umur']; ?></h6>
                    <!-- test parse data -->
                    <p class="card-text"><b>Alamat : </b> <?= $pasien['jenis kelamin']; ?></p>
                    <a href="#" class="btn btn-warning">Edit</a>
                    <a href="#" class="btn btn-danger">Hapus</a>
                    <br><br>
                    <a href="/Pages">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endsection(); ?>