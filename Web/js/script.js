$(document).ready($(function() {
	
	/*
	var i = 10;
	
	//Affichange des 10 premiers
	
	$('fieldset').hide();
	for (var j=0; j<10; j++)
	{
		$($('fieldset[data-action="Comment"]')[j]).show();
	}*/
	
	//autorefresh en gardant ceux qui étaient affichés (fonctionne mais enlève le hide dès la fin de la fonction
	/*
	var auto_refresh = setInterval(
		function refresh ()
		{
			var nbComment = 0;
			nbComment=$('fieldset[data-action="Comment"]').length;
			console.log(window.location.pathname);
			
			var refreshList = $('#commentList').clone();
			refreshList.load(window.location.pathname + ' #commentList');
			
			for (var j=i; j<nbComment; j++)
			{
				$(refreshList.find('fieldset')[j]).hide();
			}
			$(refreshList.find('fieldset')[1]).hide();
			console.log(refreshList.find('fieldset')[1]);
			console.log($( '#commentList>fieldset')[1]);
			$('#commentList').replaceWith(refreshList);
			console.log($( '#commentList>fieldset')[1]);
			
		}, 10000); // refresh every 10000 milliseconds*/
	
	
	var nbCommentDisplay = $('fieldset[data-action="Comment"]').length;
	
	var auto_refresh = setInterval(
		function refresh ()
		{
			console.log('début');
			var news = $( 'input[value="Voir plus"]').data('id');
			var postdata = $( this ).serialize()+ '&news=' + news + '&nbCommentDisplay=' + nbCommentDisplay; //requete pour savoir ce qu'il y a à refresh
			$.ajax( {
				type     : 'POST',
				url      : _url_to_refresh_comment,
				data     : postdata,
				dataType : "json",
				error    : function() {
					$( this ).prepend( '<p>Echec</p>' );
				},
				success  : function( data ) {
					actualiserCommentsUpdate( data.contenu[1] );
					actualiserCommentsDelete( data.contenu[0] );
				}
				
			} );
			console.log('fin');
			
		}, 10000);
	
	$(document).on('click','[data-action="voir-plus"]', function(event) {
		
		event.stopPropagation();
		event.preventDefault();
		
		//voir plus fonctionnel mais tout est généré
		/*
		var nbComment = 0;
		nbComment=$('fieldset[data-action="Comment"]').length;
		
		if (nbComment < i + 10)
		{
			while(i<nbComment) {
				$( $( 'fieldset[data-action="Comment"]' )[ i ] ).show();
				i = i + 1;
			}
		}
		else
		{
			var temp = i + 10;
			while(i<temp){
				$('#commentList').load(window.location.pathname + ' #commentList').fadeIn("slow");
				$($('fieldset[data-action="Comment"]')[i]).show();
				i = i + 1;
			}
		
		}*/
		
		var news = $( 'input[value="Voir plus"]').data('id');
		var postdata = $( this ).serialize()+ '&news=' + news + '&nbCommentDisplay=' + nbCommentDisplay;
		$.ajax( {
			type     : 'POST',
			url      : _url_to_show_more_comment,
			data     : postdata,
			dataType : "json",
			error    : function() {
				$( this ).prepend( '<p>Echec</p>' );
			},
			success  : function( data ) {
				afficherComments( data.contenu[0], data.contenu[1] );
				
			}
			
		} );
		
		
		
	});
	
	$(document).on('click','[data-action="voir-moins"]', function(event) {
		
		event.stopPropagation();
		event.preventDefault();
		
		// voir moins fonctionnel mais tout est généré
		/*
		if ( i - 10 < 0)
		{
			while(i>0	){
				$($('fieldset[data-action="Comment"]')[i]).hide();
				i = i - 1;
			}
		}
		else
		{
			var temp = i - 10;
			while(i>temp){
				$($('fieldset[data-action="Comment"]')[i]).hide();
				i = i - 1;
			}
		}*/
		
		
	});
	
	
	/**
	 * gère les submit (ajouter ou modifier): échange avec le php, modification en ajax puis réinitialisation quand action finie
	 */
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
					addComment( data.contenu );
					$("form")[0].reset(); // réinitialise le formulaire
					$("form")[1].reset();
				}
				
			} );
		}
		else
		{
			var contenu = $( "[name='contenu']" ).val();
			var auteur  = $( "[name='auteur']" ).val();
			var id = $( 'input[value="Modifier"]').data('id');
			var postdata = $( this ).serialize()+ '&id=' + id;
			console.log(postdata);
			$.ajax( {
				type     : $( this ).attr( 'method' ),
				url      : _url_to_update_comment,
				data     : postdata,
				dataType : "json",
				error    : function() {
					$( this ).prepend( '<p>Echec</p>' );
				},
				success  : function( data ) {
					modifyComment( data.contenu );
					console.log(data.contenu);
					$("form")[0].reset(); // réinitialise le contenu
					$("form")[1].reset();
					$('form').get(0).setAttribute('action', _url_to_insert_comment); //réinitialise l'action
					$('form').get(1).setAttribute('action', _url_to_insert_comment);
					$( 'input[value="Modifier"]' ).replaceWith( $( '<input type="submit" value="Commenter"/>' )); // réinitialise l'affichage du bouton
				}
				
			} );
		}
		
		console.log(e);
		return false;
		});
	
	/**
	 * Quand click sur modifier: scroll down, rempli le formulaire par le commentaire concerné et change 'Commenter' en 'Modifier'
	 */
	
	
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
		$('form').get(0).setAttribute('action', _url_to_update_comment);
		$('form').get(1).setAttribute('action', _url_to_update_comment);
		
		
	});
	
	/**
	 * Quand click sur supprimer, remplace le commentaire en question par  'commentaire supprimé'
	 */
	$(document).on('click','[data-action="remove-comment"]', function(event) {
		
		event.stopPropagation();
		event.preventDefault();
		$.ajax( {
			//TODO url dynamique:
			method:'POST',
			url:_url_to_remove_comment, /*'comment-delete-' + $(this).attr('data-id') + ".json", */
			data: {
				id : $(this).attr('data-id'),
			} ,
			dataType : "json",
			error: function(  ) {
				$(this).prepend('<p>Echec</p>');
			},
			success: function( data ) {
				console.log('click sur remove du commentaire' + data.contenu);
				$( "fieldset[data-id=" + data.contenu + "]" ).replaceWith( $( '<p class="msg-flash">Commentaire supprimé</p>' ).fadeIn().delay(5000).fadeOut());
			}
			
		});
		
	});
	
	/**
	 * affiche les commentaires suite au click sur voir plus
	 * @param comments
	 */
	function afficherComments(comments, loginIfIsAuthenticated) {
		

		$(comments).each( function() {
			var commentHTML = '<fieldset data-id="{{comment_id}}">' +
				'<legend>Posté par <strong>{{comment_auteur}}</strong> le {{comment_date}}' ;
			if (loginIfIsAuthenticated == -1 || loginIfIsAuthenticated == this.auteur){
				commentHTML = commentHTML +	' - <a data-action="edit-comment" data-id= {{comment_id}} data-contenu={{comment_contenu}} data-auteur="{{comment_auteur}}" href=>Modifier</a> | ' +
					'<a data-action="remove-comment" data-id= {{comment_id}} href=>Supprimer</a> ' };
			commentHTML = commentHTML +	'</legend>' +
				'<p class="comment-content" >{{comment_contenu}}</p> ' +
				'</fieldset>';
			//TODO replace all:
			commentHTML = commentHTML.replace( '{{comment_id}}', this.id.toString() );
			commentHTML = commentHTML.replace( '{{comment_id}}', this.id.toString() );
			commentHTML = commentHTML.replace( '{{comment_id}}', this.id.toString() );
			commentHTML = commentHTML.replace( '{{comment_news}}', this.news );
			commentHTML = commentHTML.replace( '{{comment_auteur}}', this.auteur );
			commentHTML = commentHTML.replace( '{{comment_auteur}}', this.auteur );
			commentHTML = commentHTML.replace( '{{comment_date}}', this.date );
			commentHTML = commentHTML.replace( '{{comment_contenu}}', this.contenu );
			commentHTML = commentHTML.replace( '{{comment_contenu}}', this.contenu );
			$( '#commentList' ).append( commentHTML);
			nbCommentDisplay = nbCommentDisplay + 1;
		});
		
		//$('#commentList').html(commentHTML);
	}
	
	/**
	 * actualise les commentaires qu'a renvoié le serveur suit a l'auto refresh
	 * @param comments
	 */
	function actualiserCommentsDelete(comments) {
		if((typeof(comments) == 'undefined') || (comments.length == 0))
		{
			return;
		}
		else {
			$( comments ).each( function() {
				$( "fieldset[data-id=" + this.id + "]" ).replaceWith( $( '<p class="msg-flash">Commentaire supprimé</p>' ).fadeIn().delay(5000).fadeOut());
			});
		}
		
		//$('#commentList').html(commentHTML);
	}
	
	
	/**
	 * actualise les commentaires qu'a renvoié le serveur suit a l'auto refresh
	 * @param comments
	 */
	function actualiserCommentsUpdate(comments) {
		if(typeof(comments) == 'undefined')
		{
			return;
		}
		else {
			$( comments ).each( function() {
				var commentHTML = '<fieldset data-id="{{comment_id}}">'+
					'<legend>Posté par <strong>{{comment_auteur}}</strong> le {{comment_date}}' +
					' - <a data-action="edit-comment" data-id= {{comment_id}} data-contenu={{comment_contenu}} data-auteur="{{comment_auteur}}" href=>Modifier</a> | ' +
					'<a data-action="remove-comment" data-id= {{comment_id}} href=>Supprimer</a> ' +
					'</legend>' +
					'<p class="comment-content" >{{comment_contenu}}</p> ' +
					'</fieldset>';
				//TODO replace all:
				commentHTML     = commentHTML.replace( '{{comment_id}}', this.id.toString() );
				commentHTML     = commentHTML.replace( '{{comment_id}}', this.id.toString() );
				commentHTML     = commentHTML.replace( '{{comment_id}}', this.id.toString() );
				commentHTML     = commentHTML.replace( '{{comment_news}}', this.news );
				commentHTML     = commentHTML.replace( '{{comment_auteur}}', this.auteur );
				commentHTML     = commentHTML.replace( '{{comment_auteur}}', this.auteur );
				commentHTML     = commentHTML.replace( '{{comment_date}}', this.date );
				commentHTML     = commentHTML.replace( '{{comment_contenu}}', this.contenu );
				commentHTML     = commentHTML.replace( '{{comment_contenu}}', this.contenu );
				$( "fieldset[data-id=" + this.id + "]" ).replaceWith( $( commentHTML ));
			});
		}
		
		//$('#commentList').html(commentHTML);
	}
	
	
	}));



/**
 * affiche le commentaire qui vient d'être ajouté
 * @param comment
 */
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
	var contenu = comment.contenu;

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

/**
 * affiche les modification qui viennent d'être faites
 * @param comment
 */

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


