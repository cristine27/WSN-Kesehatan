<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h1 class="center mb-3">Tambah Pasien</h1>
            <div class="container-md">
                <form class="w-100">
                    <div class="form-group row mb-2">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-5">
                            <input type="text" class="form-control" id="nama">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-5">
                            <textarea class="form-control" aria-label="Alamat"></textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-2">Jenis Kelamin </label>
                        <div class="col-5">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="Pria">
                                <label class="form-check-label" for="inlineRadio1">Pria</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="Wanita">
                                <label class="form-check-label" for="inlineRadio2">Wanita</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="usia" class="col-sm-2 col-form-label">Usia</label>
                        <div class="col-5">
                            <input type="number" class="form-control" id="usia">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="email" class="col-sm-2 col-form-label">E-mail</label>
                        <div class="col-5">
                            <input type="email" class="form-control" aria-label="email">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-5">
                            <input type="text" class="form-control" aria-label="password">
                        </div>
                    </div>
                    <div class="form-group row d-flex justify-content-end mt-3">
                        <div class="col-5">
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endsection(); ?>