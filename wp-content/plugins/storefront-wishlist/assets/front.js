jQuery( function ( $ ) {
	var
		$bd = $( 'body' ),
		$appWrap = $( '#wishlist' ),
		$appToggle = $( '#sfwl-app-toggle' ),
		$ifr = $( '#sfwl-app' );
	sfwl = {

		/**
		 * Recieve postMessage
		 * @param e
		 */
		receiveMessage: function ( e ) {
			var callback, payload, msg = e[e.message ? 'message' : 'data'];

			if ( msg.sfwlCallback ) {

				callback = msg.sfwlCallback;
				payload = msg.payload;

				if ( typeof sfwl.postMsgActions[callback] === 'function' ) {

					// Call post message action callback
					sfwl.postMsgActions[callback]( payload );

				}
			}
		},

		postMsgActions: {
			wishlistItems: function ( items ) {
				var host = sfwlData.host;

				$( '.sfwl-a2w.added' ).removeClass( 'added' );

				for ( var i = 0; i < items.length; i ++ ) {
					if ( items[i].indexOf( host ) > - 1 ) {
						$( 'a[onclick*="' + items[i] + '"]' ).addClass( 'added' );
					}
				}
			}
		},

		appMsg: function ( cb, payload ) {
			sfwl.appWin.postMessage( {
				sfwlCallback: cb,
				payload: payload
			}, '*' );
		},

		/**
		 * Add to wishlist
		 * @param args
		 * @param tis
		 */
		a2w: function ( args, tis ) {
			var
				$t = $( tis ),
				toggleClass = 'added-item';

			if ( $t.hasClass( 'added' ) ) {
				sfwl.appMsg( 'remove', args );
				$t.removeClass( 'added' );
				toggleClass = 'removed-item';
			} else {
				sfwl.appMsg( 'add', args );
				$t.addClass( 'added' );
			}

			$appToggle.find( 'img' ).attr(
				'src',
				$t.closest( '.product' ).find( 'img' ).filter( '[src*="wp-content/uploads"]' ).attr( 'src' )
			);
			$appToggle.addClass( toggleClass );
			setTimeout( function() {
				$appToggle.removeClass( toggleClass );
			}, 1000 );

		}
	};

	if ( $ifr.length && $ifr[0] && $ifr[0].contentWindow ) {
		sfwl.appWin = $ifr[0].contentWindow;
	}

	$appWrap.add( 'a[href="#wishlist"]' ).click( function ( e ) {
		if ( ! $( e.target ).closest( 'a[href="#wishlist"]' ).length ) {
			// Reset hash value
			history.pushState( '', document.title, window.location.pathname );
		}
		$appWrap.fadeToggle();
	} );

	window.addEventListener( 'message', sfwl.receiveMessage, false );
} );