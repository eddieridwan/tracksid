<div class="container-fluid">
    <h3 class="center">Tes Tracking Desa</h3>
    <form class="form-horizontal" action="<?php echo site_url('track/desa')?>" method="POST">
        <?php foreach ($test_data as $kolom): ?>
            <div class="form-group">
                <label class="control-label col-sm-2" for="<?= $kolom['nama'] ?>"><?= $kolom['nama'] ?>:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="<?= $kolom['nama'] ?>" name="<?= $kolom['nama'] ?>" placeholder="Enter <?= $kolom['nama'] ?>" value="<?= $kolom['nilai'] ?>">
                </div>
            </div>
        <?php endforeach; ?>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">Submit</button>
            </div>
        </div>
    </form>
</div>
