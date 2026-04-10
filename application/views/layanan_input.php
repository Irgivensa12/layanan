<h5 class="mt-4">Pilih Petugas</h5>

<?php if ($this->session->userdata('role') == 'admin') { ?>

<select name="petugas_id[]" class="form-control" multiple>
    <?php foreach ($pegawai as $p) { ?>
        <option value="<?= $p->userID ?>">
            <?= $p->pegNama ?>
        </option>
    <?php } ?>
</select>

<small class="text-muted">Tekan CTRL untuk pilih lebih dari satu</small>

<?php } ?>