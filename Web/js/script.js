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
	
	
	

	var auto_refresh = setInterval(
		function refresh ()
		{
			console.log('début');
			var news = $( 'input[value="Voir plus"]').data('id');
			var Lastid = getLastId();
			var postdata = $( this ).serialize()+ '&news=' + news + '&Lastid=' + Lastid; //requete pour savoir ce qu'il y a à refresh
			// TODO remplacer nombre de comment affiché par l'id du denrier
			$.ajax( {
				type     : 'POST',
				url      : _url_to_refresh_comment,
				data     : postdata,
				dataType : "json",
				error    : function() {
					$( this ).prepend( '<p>Echec</p>' );
				},
				success  : function( data ) {
					afficher( data.contenu.comment_updated_a, 'modification');
					afficher( data.contenu.comment_deleted_a, 'suppression');
					afficher( data.contenu.comment_added_a, 'ajout nouveau' );
				}
				
			} );
			

			console.log('fin');
			
		}, 10000);
	
	function getLastId()
	{
		var Fieldlist_a = $('fieldset[data-action="Comment"]');
		var Lastid = $('fieldset').data('id');
		Fieldlist_a.each(function() {
			if ($(this).data('id')<Lastid)
			{
				Lastid = $(this).data('id');
			}
		});
		return Lastid;
	}
	
	
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
		var Lastid = getLastId();
		var postdata = $( this ).serialize()+ '&news=' + news + '&Lastid=' + Lastid;
		$.ajax( {
			type     : 'POST',
			url      : _url_to_show_more_comment,
			data     : postdata,
			dataType : "json",
			error    : function() {
				$( this ).prepend( '<p>Echec</p>' );
			},
			success  : function( data ) {
				afficher( data.contenu.commentList,'ajout' , data.contenu.loginIfIsAuthenticated );
				
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
					afficher( [data.contenu], 'ajout nouveau', -1);
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
					afficher( [data.contenu], 'modification', -1 );
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
		var contenu = $(this).parents('fieldset').find('.comment-content').text();
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
	 * fonction d'affichage selon le type d'affichage a faire (ajout, modification, suppression)
	 * @param comments
	 */
	function afficher(comments, type, loginIfIsAuthenticated) {
		
		console.log(loginIfIsAuthenticated);
		
		if((typeof(comments) == 'undefined') || (comments.length == 0))
		{
			return;
		}
		
		$(comments).each( function() {
			var commentHTML = '<fieldset data-id="{{comment_id}}" data-action="Comment">' +
				'<legend>Posté par <strong>{{comment_auteur}}</strong> le {{comment_date}}' ;
			if (loginIfIsAuthenticated == -1 || loginIfIsAuthenticated == this.auteur){
				commentHTML = commentHTML +	' - <a data-action="edit-comment" data-id= {{comment_id}} href=>Modifier</a> | ' +
					'<a data-action="remove-comment" data-id= {{comment_id}} href=>Supprimer</a> ' };
			commentHTML = commentHTML +	'</legend>' +
				'<p class="comment-content" >' +
				'<br>';
				var Content = this.contenu.replace("(\r\n|\n|\r)", ' ');
				var Content_a = Content.split(" ");
				Content_a.forEach(function(content){
					content = content.trim();
					// TODO regex pour l'url youtube
					if ((content.match(/https:\/\/www.youtube.com\/watch\?v=/g)).length != 0)
					{
						commentHTML = commentHTML + '<a href={{content}}>>Lien Youtube</a>';
						commentHTML = commentHTML.replace('{{content}}', content );
						commentHTML = commentHTML +
							'<object width="425" height="344">' +
							'<param name="movie" value="http://www.youtube.com/v/{{subcontent}}"></param>'+
							'<param name="allowFullScreen" value="true"></param>' +
							'<param name="allowscriptaccess" value="always"></param>' +
 							'<embed src="http://www.youtube.com/v/{{subcontent}}" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed>' +
							'</object>';
						commentHTML = commentHTML.replace('{{subcontent}}', content.substr(32) );
						commentHTML = commentHTML.replace('{{subcontent}}', content.substr(32) );
						
						//this.contenu = this.contenu.replace(content, '');
					}
										
				});
				commentHTML = commentHTML +
				'<br>' +
				'{{comment_contenu}}' +
				'</p>' +
				'</fieldset>';
				
			
			
			
			//TODO replace all:
			commentHTML = commentHTML.replace( /{{comment_id}}/g, this.id.toString() );
			commentHTML = commentHTML.replace( '{{comment_news}}', this.news );
			commentHTML = commentHTML.replace( '{{comment_auteur}}', this.auteur );
			commentHTML = commentHTML.replace( '{{comment_date}}', this.date );
			commentHTML = commentHTML.replace( '{{comment_contenu}}', this.contenu );
	
		
		if (type == 'ajout nouveau')
		{
			if ($( "fieldset[data-id=" + this.id + "]" ).length != 0) // test pour ne pas  ré-afficher un commentaire que l'on vient d'ajouter lors de l'auto refresh
			{
				return;
			}
			$( '#commentList' ).prepend( commentHTML);
			
		}
			
		else if (type == 'ajout')
		{
			$( '#commentList' ).append( commentHTML);
				
		}
		
		else if (type == 'modification')
		{
			
			$( "fieldset[data-id=" + this.id + "]" ).replaceWith( $( commentHTML ));
		}
		
		else if (type == 'suppression')
		{
			$( "fieldset[data-id=" + this.id + "]" ).replaceWith( $( '<p class="msg-flash">Commentaire supprimé</p>' ).fadeIn().delay(5000).fadeOut());
		}
		
		});
		
		
	}
	
	
	}));







