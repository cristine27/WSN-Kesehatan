<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-8">
            <h1 class="center mb-3">Form Tambah Pasien</h1>
            <!-- tampilin semua error $validation->listErrors();-->

            <div class="container-md">
                <form action="/Pasien/savePasien" method="POST" class="w-100">
                    <?= csrf_field(); ?>
                    <div class="form-group row mb-2">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-5">
                            <input type="text" class="form-control <?= ($validation->hasError('nama') ? 'is-invalid' : ''); ?>" id="nama" name="nama" autofocus value=<?= old('nama'); ?>>
                            <div class="invalid-feedback">
                                <?= $validation->getError('nama'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-5">
                            <textarea class="form-control" aria-label="Alamat" name="alamat" value=<?= old('alamat'); ?>></textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-2">Jenis Kelamin </label>
                        <div class="col-5">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenisKelamin" id="opPria" value="Pria">
                                <label class="form-check-label" for="opPria">Pria</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenisKelamin" id="opWanita" value="Wanita">
                                <label class="form-check-label" for="opWanita">Wanita</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="usia" class="col-sm-2 col-form-label">Usia</label>
                        <div class="col-5">
                            <input type="number" class="form-control" id="usia" name="usia" value=<?= old('usia'); ?>>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="email" class="col-sm-2 col-form-label">E-mail</label>
                        <div class="col-5">
                            <input type="email" class="form-control" aria-label="email" name="email" value=<?= old('email'); ?>>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-5">
                            <input type="text" class="form-control" name="password" aria-label="password" value=<?= old('password'); ?>>
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