<h2>Connexion</h2>

<form action="#" method="post" id="form-login">
	<?= $form_edit ?>
</form>

<hr>
<p class="account-link"><a href="index.php?p=register">S'inscrire</a></p>

<script type="text/javascript">
$(document).ready(function() {
	
	$('#form-login').submit(function(event) {
		event.preventDefault();
	    $.post(
	    	"index.php?p=login",
	    	$(this).serialize(),
	    	function(data){
				if (data['success']) {
		            window.location.href = "index.php?p=account";
		        } else {
		        	$('#form-password').val('');
		        	$('.alert').remove();
		        	$('h2').after("<p class='alert alert-danger'>Adresse mail ou mot de passe incorrect</p>");
		        }
			},
			'json'
		);
	});
});
</script>