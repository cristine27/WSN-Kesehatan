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
                        <th scope="col">Email</th>
                        <th scope="col">Password</th>
                        <th scope="col">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row"><?= $i++; ?></th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        <td>pass</td>
                        <td><button type="button" class="btn btn-info">Detail</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endsection(); ?>