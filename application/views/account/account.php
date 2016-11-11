<h2>Mon compte</h2>

<form action="index.php?p=edit_info" method="post" id="form-edit-info">
	<?= $form_edit_info; ?>
</form>

<form action="index.php?p=edit_pass" method="post" id="form-edit-pass">
	<?= $form_edit_pass; ?>
</form>

<button id="edit-info-btn">Modifier mes informations</button>
<button id="edit-pass-btn">Modifier mon mot de passe</button>
<a class="button" id="disconnect-btn" href="index.php?p=disconnect">Déconnexion</a>

<script type="text/javascript">
$(document).ready(function() {

	// Initialisation et commun

	function init() {
		$('#form-edit-pass').hide();
		$('#form-edit-pass input').val('');
		$("#form-edit-info input").attr("disabled", "disabled");
		$('#cancel-btn').remove();
		$('#form-edit-info button').hide();
		$('#edit-info-btn').show();
		$('#edit-pass-btn').show();
		$('#disconnect-btn').show();
		$("#form-edit-info input:hidden").each(function() {
			$(this).remove();
		});
	}

	function add_cancel_button() {
		$('#disconnect-btn').after("<button id='cancel-btn'>Annuler</button>");
		$('#cancel-btn').on('click', function() {
			$(".save").each(function() { // Annule les modifications faites
				name = $(this).attr('id');
				name = name.substr(0, name.indexOf('-save'));
				$('#'+name).val($(this).val());
			});
			clear_alert();
			init();
		});
	}

	function clear_alert() {
		$('.alert').remove();
	}

	init();

	// Edition des informations personnelles

	$('#edit-info-btn').on('click', function() {
		clear_alert();
		$('#form-edit-info button').show();
		$('#edit-info-btn').hide();
		$('#edit-pass-btn').hide();
		$('#disconnect-btn').hide();
		$("#form-edit-info input").removeAttr("disabled");
		add_cancel_button();
		$("input:visible").each(function() {
			$('#form-edit-info').append("<input class='save' type='hidden' id='"+$(this).attr('id')+"-save' value='"+$(this).val()+"'>");
		});
	});

	// Edition du mot de passe

	$('#edit-pass-btn').on('click', function() {
		clear_alert();
		$('#form-edit-pass').show();
		$('#edit-info-btn').hide();
		$('#edit-pass-btn').hide();
		$('#disconnect-btn').hide();
		add_cancel_button();
	});
	
	// Envoi du formulaire

	$('form').submit(function(event) {
		event.preventDefault();
		clear_alert();
	    $.post(
	    	$(this).attr('action'),
	    	$(this).serialize(),
	    	function(data){
				if (data['success']) {
		            $('h2').after("<p class='alert alert-success'>Vos informations ont été mises à jour avec succès !</p>");
		        	init();
		        } else {
		        	$.each(data['errors'], function(input_name, input_value){
		        		$('#form-edit-pass input').val('');
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