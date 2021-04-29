<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="container">
                <table>
                    <tbody>
                        <tr>
                            <?= $dd($dataPasien); ?>
                            <td>Nama</td>
                            <td>:</td>
                            <td>Cristine</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endsection(); ?>