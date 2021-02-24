<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Error</title>
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/error.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="xs-12 md-6 mx-auto">
            <div id="infobox">
                <div class="message">
                    <img src="<?php echo SITE_URL; ?>assets/images/sitecontent/warning.png" /><br>
<?php echo $this->msg; ?><br>
<a href="<?php echo APP_URL; ?>"><?php echo STR_GOBACK; ?></a>
                   </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
