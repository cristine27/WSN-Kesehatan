<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container">
    <div class="row">
        <div class="col-6">
            <h1 class="mt-2">Daftar Pasien</h1>
            <form action="" method="POST">
                <div class="input-group mt-3">
                    <input type="text" name="pencarian" class="form-control" aria-describedby="searchbtn" aria-label="search" placeholder="Masukkan nama pasien..">
                </div>
                <div class="input-group-append">
                    <button name="btnCari" class="btn btn-outline-secondary" type="submit">Cari</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <a href="/Pasien/addPasien" class="btn btn-primary mt-3">Tambah Pasien</a>

            <?php if (session()->getFlashdata('pesan')) : ?>
                <div class="alert alert-success" role="alert">
                    <?= session()->getFlashdata('pesan'); ?>
                </div>
            <?php endif; ?>


            <table class="table">
                <?php $i = 1 + (6 * ($currentPage - 1)) ?>
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">idPasien</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Umur</th>

                        <th scope="col">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pasien as $p) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?></th>
                            <td><?= $p['idPasien']; ?></td>
                            <td><?= $p['nama']; ?></td>
                            <td><?= $p['gender']; ?></td>
                            <td><?= $p['umur']; ?></td>
                            <td><a href="/Pasien/detail/<?= $p['idPasien']; ?>" class="btn btn-success">Detail</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?= $pager->links('pasien', 'pasien_pagination'); ?>
        </div>
    </div>
</div>
<?= $this->endsection(); ?>