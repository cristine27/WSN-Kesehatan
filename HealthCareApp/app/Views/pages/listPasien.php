<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container">
    <div class="row">

        <div class="col">

            <div class="input-group w-25 my-3 float-end">
                <input type="text" class="form-control" id="search" aria-describedby="searchbtn" aria-label="search">
                <button class="btn btn-outline-secondary" type="button" id="searchbtn">Cari</button>
            </div>

            <table class="table">
                <?php $i = 1 ?>
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">idPasien</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Jenis Kelamin</th>
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
                            <td><?= $p['jenis kelamin']; ?></td>
                            <td><?= $p['umur']; ?></td>
                            <td><a href="/Pasien/<?= $p['idPasien']; ?>" class="btn btn-success">Detail</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endsection(); ?>