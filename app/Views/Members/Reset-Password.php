<div class="row">
    <div class="col-xs-12">
        <h1><?php echo $data['title']; ?></h1>
    </div>
</div>
<?php
if(isset($data['message'])) {
    ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="alert alert-<?php echo $data['type']=="success" ? "success" : "danger"; ?>" role="alert">
                <?php echo isset($data['message']) ? $data['message'] : ""; ?>
            </div>
        </div>
    </div>
    <?php
}
?>
<div class="row">
    <form class="form" method="post">
        <div class="col-xs-12">
            <div class="form-group">
                <label class="control-label">New Password</label>
                <input  class="form-control" type="password" id="password" name="password" placeholder="New Password">
            </div>
            <div class="form-group">
                <label class="control-label">Confirm New Password</label>
                <input  class="form-control" type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm New Password">
            </div>
            <input type="hidden" name="token_resetpassword" value="<?= $data['csrfToken']; ?>" />
            <button class="btn btn-primary" type="submit" name="submit">Change my Password</button>
        </div>

    </form>
</div>
