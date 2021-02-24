	<section class="block-wrap">
      <div class="container-fluid">
		<div class="row ">
            <div class="col-md-2">
                <div class="sidebar">
                    <div class="sidebarheader">
                    Filters
                    </div>
                    <div class="sidebarcontent">
                        <form action="" method="post">
                        <p><?php echo STR_GENDER; ?>: <input type="radio" name="gender" id="optmale" value="male"
                        <?php
                        if (isset($_POST['gender']) && ($_POST['gender'] == "male"))
                            echo 'checked=""';
                        ?>></input> <label for="male" style="color: #68b7d9;">M</label>
                        <input type="radio" name="gender" value="female" id="optfemale" 
                        <?php
                        if (isset($_POST['gender']) && ($_POST['gender'] == "female"))
                            echo 'checked=""';
                        ?>></input> <label for="female" style="color: #d9689d;">F</label></p>
                        <p><?php echo STR_COUNTRY; ?>: <input type="text" value="<?php echo $this->myself['country']; ?>" name="country" disabled="" /></p>
                        <p><?php echo STR_CITY; ?>: <input type="text" value="<?php
                        if (isset($_POST['city']))
                            echo $_POST['city'];
                        //else
                        //    echo $this->myself['city'];
                        ?>" name="city" /></p>
                        <p><?php echo STR_AGE; ?>: <input type="number" value="<?php
                        if (isset($_POST['fromage'])) {
                            echo $_POST['fromage'];
                        } else {
                            $baseage=getAge($this->myself['borndate']) - 5;
                            if ($baseage < 18)
                                $baseage = 18;
                            echo $baseage;
                        }
                        ?>" name="fromage" min="18" max="99" />
                        - <input type="number" value="<?php
                        if (isset($_POST['toage'])) {
                            echo $_POST['toage'];
                        } else {
                            echo (getAge($this->myself['borndate']) + 5);
                        }
                        ?>" name="toage" min="18" max="99" /></p>
                        <p><label for="withphoto" id="lblWithPhoto"><?php echo STR_WITHPHOTO; ?>: </label><input type="checkbox" id="withphoto" name="withphoto" value="1" <?php
                            if (isset($_POST['withphoto']))
                                echo 'checked=""';
                        ?>/></p>
                        <p><button type="submit" id="buttonApplyFilter" class="sidebarbutton"><?php echo STR_APPLY; ?></button></p>
                        </form>
                    </div>
                </div>
            </div>
			<div class="col-md-8">
				<div class="row photos-wrap" style="text-align: center">
                <div class="searchresults">
                <div class="resultsdesc">Results: <?php echo count($this->users); ?></div>
                    <?php
                        foreach($this->users as &$user) {
                            echo '<div class="searchresult" data-username="'.$user['username'].'">';
                            echo '<img src="'.SITE_URL.'assets/images/profile/'.$user['profile_image'].'"></img>';
                            echo '<div class="profileinfo">'.$user['username'].'<br>'.getAge($user['borndate']).' '.STR_YEARS.'<br>'.$user['city'].'</div></div>';
                        }
                    ?>
				</div>
                </div>
			</div>
		</div>
	</div>
	</section>

