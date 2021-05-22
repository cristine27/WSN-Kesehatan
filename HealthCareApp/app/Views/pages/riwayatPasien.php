<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
</head>
<script type="text/javascript">
    $(function() {
        $(".datepicker").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });

        $("#tgl_mulai").on('changeDate', function(selected) {
            var startDate = new Date(selected.date.valueOf());
            $("#tgl_akhir").datepicker('setStartDate', startDate);
            if ($("#tgl_mulai").val() > $("#tgl_akhir").val()) {
                $("#tgl_akhir").val($("#tgl_mulai").val());
            }
        });
    });

    $(document).ready(function() {
        var tglAwal = $('input[name="tgl_awal"]'); //our date input has the name "tgl_awal"
        var tglAkhir = $('input[name="tgl_akhir"]'); //our date input has the name "tgl_akhir"

        alert("ini tanggal awal ", tglAwal);
    })
</script>

<body>
    <div class="container">
        <div class="row">
            <a href="/Pasien/detail/<?= $pasien['idPasien']; ?>">Kembali</a>
        </div>
        <div class="row">
            <div class="col w-50">
                <h3>Filter</h3>
                <form method="POST">
                    <div class="form-group">
                        <label>Tgl Awal</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                            <input placeholder="masukkan tanggal Awal" type="text" class="form-control datepicker" name="tgl_awal">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tgl Akhir</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                            <input placeholder="masukkan tanggal Akhir" type="text" class="form-control datepicker" name="tgl_akhir">
                        </div>
                    </div>
                    <div class="form-group">
                        <!-- Submit button -->
                        <button class="btn btn-primary " name="submit" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card w-75 mt-5">

                    <h4 class="card-header">Riwayat Pemeriksaan</h4>

                    <div class="card-body">
                        <p class="<?= ($flag == true) ? 'invisible' : 'visible'; ?>">Pasien Belum Melakukan Pemeriksaan</p>
                        <?php for ($index = 0; $index < $jumlahHasil; $index++) { ?>
                            <h5 class="mt-5">Hasil Pemeriksaan : <?= $hasilPeriksa[$index]['waktu']; ?></h5>
                            <div class="col d-flex justify-content-start">
                                <div class="w-75 table-responsive">
                                    <table class="table table-striped text-center">
                                        <thead class="table-dark">
                                            <tr>
                                                <th scope="col">Parameter</th>
                                                <th scope="col">Hasil</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <?php
                                        for ($i = 0; $i < 3; $i++) { ?>
                                            <tbody>
                                                <tr>
                                                    <th scope="row"><?= $parameter[$index][$i]['namaParameter']; ?></th>
                                                    <td><?= $hasilPeriksa[$index]['hasil' . strval($i + 1)]; ?></td>
                                                    <td>
                                                        <p class="text-<?= $status[$index][$i] == "normal" ? 'success' : 'danger'; ?>"><?= $status[$index][$i]; ?></p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        <?php } ?>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= $this->endsection(); ?>