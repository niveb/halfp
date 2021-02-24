<section class="profile-wrap">
<div class="user_profile_information">
<div class="container-fluid">
<div class="row">
<div class="col-sm-6" style="float: none; margin: 0 auto;">
<div class="profile_settings_box">
<?php if(!empty($this->messages)) { echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">x</button><strong>Ops! </strong>'.$this->messages.'.</div>'; } ?>

    <div class="profile_username"><?php echo $this->userData['username']; ?></div>

	<div class="image_upload_box">
		<img src="<?php echo SITE_URL . "assets/images/profile/".$this->userData['profile_image']; ?>" id="preview" class="user_profile_image">
			<form enctype="multipart/form-data" method="post" action="">

					<label class="profileUpload btn btn-default">
						<input type="file"  id="file" style="display:none;" class="upload" name="img" accept="image/jpg,image/png,image/jpeg,image/gif">
						<span class="upload"><?php echo STR_UPLOADPROFILEPHOTO; ?></span>
					</label>
		</div>
<div class="bio_box">
<label><?php echo STR_BIO; ?></label>
<textarea id="bio" name="bio" rows="4" cols="30" placeholder="<?php echo STR_ADDBIO; ?>" maxlength="250">
<?php echo $this->userData['profile_bio']; ?>
</textarea>
</div>
<div class="options">
    <input type="checkbox" id="searchable" name="searchable" <?php
    if (($this->userData['searchable'] == 1) || isset($_POST['searchable'])) echo 'checked=""';
    ?>><label for="searchable"><?php echo STR_SEARCHABLE; ?></label>
    <br>
    <input type="checkbox" id="delete" name="delete"><label id="deletelbl" for="delete"><?php echo STR_DELETEACCOUNT; ?></label>
    <button id="deletebtn" type="button" name="deletebtn" class="edit_settings delete" style="display: none;">Delete</button>
</div>


	<input type="submit" name="submit" class="edit_settings save" value="<?php echo STR_SAVE; ?>">
	<button name="cancel" class="edit_settings cancel"><?php echo STR_CANCEL; ?></button>
	</form>
</div>
</div> <!-- row -->
</div>
</div>
</section>
<?php //include("includes/footer.php"); ?>
</body>
</html>
