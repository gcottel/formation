$(document).ready($(function() {
	
	$('#commentform1, #commentform2').submit(function( e )
	{
		e.stopPropagation();
		e.preventDefault();
		var contenu = $("[name='contenu']").val();
		var auteur = $("[name='auteur']").val();
		$.ajax( {
			type: $(this).attr('method'),
			url: $(this).attr('action'),
			data: $(this).serialize(),
			dataType : "json",
			error: function(  ) {
				$(this).prepend('<p>Echec</p>');
			},
			success: function( data ) {
				//console.log('0');
				//console.log(data);
				//console.log('1');
				//console.log(data.contenu);
				//console.log('2');
				//console.log((data.contenu)[0]["news"]);
				//console.log('iiiiiiii');
				//console.log(data.contenu.news);
				addComment(data.contenu);
			}
				
			});
		
		console.log(e);
		return false;
		});
	}));
	



	
function addComment(comment) {

	var commentHTML = '<fieldset data-id="{{comment_id}}">'+
		'<legend>Posté par <strong>{{comment_auteur}}</strong> le {{comment_date}}' +
		'</legend>' +
		'<p class="contenu">{{comment_contenu}}</p> ' +
		'</fieldset>';
	//var date = new Date(année, mois[, jour[, heures[, minutes[, secondes[, millisecondes]]]]]);
	//var datestring  = ("0" + date.getDate()).slice( -2 ) + '/' + ("0" + date.getMonth()).slice( -2 ) + '/' + date.getFullYear() + ' à ' + ("0" + date.getHours()).slice( -2 ) + 'h' + ("0" + date.getMinutes()).slice( -2 );
	commentHTML = commentHTML.replace('{{comment_news}}', comment.news);
	commentHTML = commentHTML.replace('{{comment_auteur}}', comment.auteur);
	commentHTML = commentHTML.replace('{{comment_date}}', comment.date);
	commentHTML = commentHTML.replace('{{comment_contenu}}', comment.contenu);
	$('#commentList').append(commentHTML);
	//$('#commentList').html(commentHTML);
}
