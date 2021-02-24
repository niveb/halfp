$(document).ready(function() {
    $("#deletebtn").click(function() {
        if (confirm("Continue?")) {
            $.get(url + "settings/deleteMe").done(function() { window.location.href = "/"; });
        }
    });
    $("#delete").click(function() {
        $("#deletebtn").toggle();
    });
    $(".cancel").click(function() {
        window.history.back();
    });

		 $("#file").change(function() {
			  var fileExtension = ['png','gif','jpeg','jpg'];
              if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                        alert("Invalid file type");
                        this.value = ''; // Clean field
                        return false;
                    } else {
			 var get = new FileReader();
			 get.onload = function(e) {
				 
				 $('#preview').attr('src', e.target.result);
				 
			 }
			 get.readAsDataURL(this.files[0]);
					}
		 });

	 
 });
