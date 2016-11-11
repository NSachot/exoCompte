<h2>Création de compte</h2>

<div id="alert-register"></div>

<form action="#" method="post" id="form-register">
	<?= $form_edit; ?>
</form>

<hr>
<p class="account-link"><a href="index.php?p=login">Se connecter</a></p>

<script type="text/javascript">
$(document).ready(function() {

	function clear_alert() {
		$('.alert').remove();
	}

	$('#form-register').submit(function(event) {
		event.preventDefault();
		clear_alert();
	    $.post(
	    	"index.php?p=register",
	    	$(this).serialize(),
	    	function(data){
				if (data['success']) {
		            window.location.href = "index.php?p=account";
		        } else {
		        	$('#form-password').val('');
		        	$('#form-password-confirm').val('');
		        	$.each(data['errors'], function(input_name, input_value){
		        		html = '<ul class="alert alert-danger alert-danger-mini">';
		        		$.each(input_value, function(key, value){
		        			html += '<li>'+value+'</li>';
		        		});
		        		html += '</ul>';
		        		$('#form-'+input_name).after(html);
		        	});
		        }
			},
			'json'
		);
	});
});
</script>