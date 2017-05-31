$(document).ready($(function() {
	
	$('#commentform1, #commentform2').on('submit',function( e )
	{
		e.stopPropagation();
		e.preventDefault();
		
		if ($( 'input[value="Commenter"]' ).length)
		{
			var contenu = $( "[name='contenu']" ).val();
			var auteur  = $( "[name='auteur']" ).val();
			$.ajax( {
				type     : $( this ).attr( 'method' ),
				url      : $( this ).attr( 'action' ),
				data     : $( this ).serialize(),
				dataType : "json",
				error    : function() {
					$( this ).prepend( '<p>Echec</p>' );
				},
				success  : function( data ) {
					//console.log('0');
					//console.log($(this).attr('action'));
					//console.log(data);
					//console.log('1');
					//console.log(data.contenu);
					//console.log('2');
					//console.log((data.contenu)[0]["news"]);
					//console.log('iiiiiiii');
					//console.log(data.contenu.news);
					//console.log($(this).attr('action'));
					addComment( data.contenu );
				}
				
			} );
		}
		else
		{
			var contenu = $( "[name='contenu']" ).val();
			var auteur  = $( "[name='auteur']" ).val();
			var id = $( 'input[value="Modifier"]').data('id');
			var postdata = $( this ).serialize() + id;
			console.log($( this ).attr( 'action' ));
			console.log(id);
			$.ajax( {
				type     : $( this ).attr( 'method' ),
				url      : $( this ).attr( 'action' ),
				data     : postdata,
				dataType : "json",
				error    : function() {
					$( this ).prepend( '<p>Echec</p>' );
				},
				success  : function( data ) {
					//console.log('0');
					//console.log($(this).attr('action'));
					//console.log(data);
					//console.log('1');
					//console.log(data.contenu);
					//console.log('2');
					//console.log((data.contenu)[0]["news"]);
					//console.log('iiiiiiii');
					//console.log(data.contenu.news);
					//console.log($(this).attr('action'));
					console.log(data.contenu);
					modifyComment( data.contenu );
				}
				
			} );
		}
		
		console.log(e);
		return false;
		});
	
	
	
	$(document).on('click','[data-action="edit-comment"]', function(event) {
		
		event.stopPropagation();
		event.preventDefault();
		
		window.scrollTo(0, document.body.scrollHeight);
		var id = $(this).attr('data-id');
		var contenu = $(this).attr('data-contenu');
		var auteur = $(this).attr('data-auteur');
		$(':text').val(auteur);
		$('textarea[name=contenu]').val(contenu);
		$( 'input[value="Commenter"]' ).replaceWith( $( '<input type="submit" value="Modifier" data-id=' + id + ' />' ));
		$('form').get(0).setAttribute('action', '/comment-update-' + id + '.json');
		$('form').get(1).setAttribute('action', '/comment-update-' + id + '.json');
		

		
		/*
		var html = '<form action="/comment-update-' + id + '.json"' + 'id="comment-update">' +
			'<textarea name="comment" form="usrform">' + contenu + '</textarea>' +
		'<input type="submit" value = "Modifier">' +
		'</form>';
		$( "fieldset[data-id=" + id + "]" ).replaceWith( $( html ));*/
		
		
	});
	
	$(document).on('click','[data-action="remove-comment"]', function(event) {
		
		event.stopPropagation();
		event.preventDefault();
		$.ajax( {
			//TODO url dynamique:
			url:/*_url_to_remove_comment*/'comment-delete-' + $(this).attr('data-id') + ".json",
			data: $(this).serialize(),
			dataType : "json",
			error: function(  ) {
				$(this).prepend('<p>Echec</p>');
			},
			success: function( data ) {
				console.log('click sur remove du commentaire' + data.contenu);
				$( "fieldset[data-id=" + data.contenu + "]" ).replaceWith( $( '<p class="msg-flash">Commentaire supprimé</p>' ));
			}
			
		});
		
		/*
		var id = $(this).attr('data-id');
		$( "fieldset[data-id=" + id + "]" ).replaceWith( $( '<p class="msg-flash">Commentaire supprimé</p>' ));
		*/
		
		//console.log('click sur remove du commentaire' + data.contenu);
	});
	}));

/*function deleteComment( comment ) {
	console.log( 'deleteComment' + comment.id );
	//$( "fieldset[data-id=" + comment_id + "]" ).replaceWith( $( '<p class="msg-flash' + rand + '">Commentaire supprimé</p>' ).hide().fadeIn() );
}*/



	
function addComment(comment) {
	
	var commentHTML = '<fieldset data-id="{{comment_id}}">'+
		'<legend>Posté par <strong>{{comment_auteur}}</strong> le {{comment_date}}' +
		' - <a data-action="edit-comment" data-id= {{comment_id}} data-contenu={{comment_contenu}} data-auteur="{{comment_auteur}}" href=>Modifier</a> | ' +
		'<a data-action="remove-comment" data-id= {{comment_id}} href=>Supprimer</a> ' +
		'</legend>' +
		'<p class="comment-content" >{{comment_contenu}}</p> ' +
		'</fieldset>';
	//var date = new Date(année, mois[, jour[, heures[, minutes[, secondes[, millisecondes]]]]]);
	//var datestring  = ("0" + date.getDate()).slice( -2 ) + '/' + ("0" + date.getMonth()).slice( -2 ) + '/' + date.getFullYear() + ' à ' + ("0" + date.getHours()).slice( -2 ) + 'h' + ("0" + date.getMinutes()).slice( -2 );
	//TODO replace all:
	commentHTML = commentHTML.replace('{{comment_id}}', comment.id.toString());
	commentHTML = commentHTML.replace('{{comment_id}}', comment.id.toString());
	commentHTML = commentHTML.replace('{{comment_id}}', comment.id.toString());
	commentHTML = commentHTML.replace('{{comment_news}}', comment.news);
	commentHTML = commentHTML.replace('{{comment_auteur}}', comment.auteur);
	commentHTML = commentHTML.replace('{{comment_auteur}}', comment.auteur);
	commentHTML = commentHTML.replace('{{comment_date}}', comment.date);
	commentHTML = commentHTML.replace('{{comment_contenu}}', comment.contenu);
	commentHTML = commentHTML.replace('{{comment_contenu}}', comment.contenu);
	$('#commentList').append(commentHTML);
	//$('#commentList').html(commentHTML);
}

function modifyComment(comment) {
	
	var commentHTML = '<fieldset data-id="{{comment_id}}">'+
		'<legend>Posté par <strong>{{comment_auteur}}</strong> le {{comment_date}}' +
		' - <a data-action="edit-comment" data-id= {{comment_id}} data-contenu={{comment_contenu}} data-auteur="{{comment_auteur}}" href=>Modifier</a> | ' +
		'<a data-action="remove-comment" data-id= {{comment_id}} href=>Supprimer</a> ' +
		'</legend>' +
		'<p class="comment-content" >{{comment_contenu}}</p> ' +
		'</fieldset>';
	//var date = new Date(année, mois[, jour[, heures[, minutes[, secondes[, millisecondes]]]]]);
	//var datestring  = ("0" + date.getDate()).slice( -2 ) + '/' + ("0" + date.getMonth()).slice( -2 ) + '/' + date.getFullYear() + ' à ' + ("0" + date.getHours()).slice( -2 ) + 'h' + ("0" + date.getMinutes()).slice( -2 );
	//TODO replace all:
	commentHTML = commentHTML.replace('{{comment_id}}', comment.id.toString());
	commentHTML = commentHTML.replace('{{comment_id}}', comment.id.toString());
	commentHTML = commentHTML.replace('{{comment_id}}', comment.id.toString());
	commentHTML = commentHTML.replace('{{comment_news}}', comment.news);
	commentHTML = commentHTML.replace('{{comment_auteur}}', comment.auteur);
	commentHTML = commentHTML.replace('{{comment_auteur}}', comment.auteur);
	commentHTML = commentHTML.replace('{{comment_date}}', comment.date);
	commentHTML = commentHTML.replace('{{comment_contenu}}', comment.contenu);
	commentHTML = commentHTML.replace('{{comment_contenu}}', comment.contenu);
	$( "fieldset[data-id=" + comment.id + "]" ).replaceWith( $( commentHTML ));
	//$('#commentList').html(commentHTML);
}
