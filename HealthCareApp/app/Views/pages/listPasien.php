<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
</head>

<body>
    <div class="container">
        <div class="row">

            <div class="col-6">
                <h1 class="mt-2 d-inline float-start">Daftar Pasien</h1>
            </div>
            <div class="col-6">
                <form action="" method="POST" class="mt-2">
                    <div class="input-group mb-3">
                        <input type="text" name="pencarian" class="form-control" aria-describedby="searchbtn" aria-label="search" placeholder="Masukkan nama pasien..">
                        <button name="Submit" class="btn btn-outline-secondary" type="submit">Cari</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <a href="/Pasien/addPasien" class="btn btn-primary mt-3 d-inline">Tambah Pasien</a>
                <a role="button" class="btn btn-danger float-end d-inline" href="/SignIn">Log Out</button>
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
                                <th scope="col">Alamat</th>
                                <th scope="col">Email</th>
                                <th scope="col">Password</th>

                                <th scope="col">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pasien as $p) : ?>
                                <tr>
                                    <th scope="row"><?= $i++; ?></th>
                                    <td><?= $p['idPasien']; ?></td>
                                    <td><?= $p['nama']; ?></td>
                                    <td><?= $p['alamat']; ?></td>
                                    <td><?= $p['email']; ?></td>
                                    <td><?= $p['password']; ?></td>
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