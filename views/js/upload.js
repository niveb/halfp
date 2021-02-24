 $(document)
 	.ready(function() {
 		$(function() {
 			$("#file")
 				.change(function() {
 					var fileExtension = ['png', 'gif', 'jpeg', 'jpg'];
 					if ($.inArray($(this)
 							.val()
 							.split('.')
 							.pop()
 							.toLowerCase(), fileExtension) == -1) {
 						alert("Invalid file type");
 						this.value = '';
 						return false;
 					} else {
 						var get = new FileReader();
 						get.onload = function(e) {
 							$('#preview')
 								.attr('src', e.target.result);
 						}
 						get.readAsDataURL(this.files[0]);
 					}
 				});
 		});
 });
