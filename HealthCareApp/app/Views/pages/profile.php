<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="container">
                <table>
                    <tbody>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td><?= $dataPasien['nama']; ?></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td><?= $dataPasien['alamat']; ?></td>
                        </tr>
                        <tr>
                            <td>Umur</td>
                            <td>:</td>
                            <td><?= $dataPasien['umur']; ?></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>:</td>
                            <td><?= $dataPasien['email']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endsection(); ?>