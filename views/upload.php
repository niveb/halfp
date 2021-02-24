<section class="block-wrap">
      <div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="block-content">
					<div class="row photos-wrap">
						<div id="main_container">
						
						<?php if(!empty($this->msg)) { echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">x</button><strong>Ops! </strong>'.$this->msg.'.</div>'; } ?>
						
						<form enctype="multipart/form-data" method="post" action="">
						
							<div class="photo-box">
						<div class="top_name">
					<label class="fileUpload btn btn-default">
						<input type="file" id="file" style="display:none;" name="img" accept="image/jpg,image/png,image/jpeg,image/gif">
						<span class="upload"><?php echo STR_UPLOAD; ?></span>
					</label>
				</div>
				<div class="upload-wrap">
				
				<img id="preview" src="<?php echo SITE_URL; ?>assets/images/upload.png">
				
		
				</div>
				
				<div class="description">
					

				<div class="user_name_feedbox"><?php echo STR_ADDPOSTDESCRIPTION; ?></div>
						<textarea name="desc" id="description" rows="4" cols="60" style="width:80%" maxlength="250"></textarea>
						<input class="button" type="submit" name="submit" value="<?php echo STR_PUBLISH; ?>">
		
		
				</div>
				</div>
				</form>
						
						
						
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
