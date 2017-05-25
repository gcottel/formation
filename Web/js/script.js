/**
 * Created by gcottel on 25/05/2017.
 */
$(document).ready(function() {
	// Au submit du formulaire
	$('#form').submit( function() {
		var pseudo = $('#pseudo').val();
		var commentaire = $('#contenu').val();
		// On ajoute le nouvel élément
		$('#commentaire').prepend('<p class="last" id="com_'+nbCom+'"><strong>'+pseudo+'</strong> a dit :<br />'+commentaire+'</p>');
		// On efface le contenu du formulaire
		$('#contenu').val('').focus();
		// On retourne false pour ne pas recharger la page
		return false;
	});
});