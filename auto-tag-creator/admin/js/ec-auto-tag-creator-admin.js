function auto_tag_save_settings() {
	
	var setting_from = jQuery('form#tag_setting_from').serialize();
	
	jQuery.ajax({
		type: "POST",
		url: ajaxurl,
		data:'action=tag_save_settings&security='+ec_auto_tag_creator.security+'&'+setting_from,
		beforeSend: function() {
			jQuery("#submit_settings").prop('disabled', true);
			jQuery(".success_message").hide("slow");			
			jQuery(".loader_image").show("slow");			
		},
		success: function(data){
			jQuery(".loader_image").hide("slow");
			if(data.success) {
				jQuery(".ec-atc-modal-warning").prop("hidden", ! jQuery("#overwrite_tag").is(":checked"));
				jQuery("#tag_setting_from").before('<div class="success_message notice notice-success"><p><strong>'+data.msg+'</strong></p></div>');
				jQuery("#submit_settings").prop('disabled', false);
				setTimeout(function() { jQuery(".success_message").hide("slow"); }, 3000);
			}
		}
	});
	return false;
}

function ec_auto_tag_format( text ) {
	var args = Array.prototype.slice.call( arguments, 1 );
	return text.replace( /%(\d+)\$s/g, function( match, index ) {
		return args[ index - 1 ];
	} ).replace( '%s', args[0] );
}

function ec_auto_tag_bulk_finish( status, state, text ) {
	status.text( text ).closest( '.ec-atc-bulk-label' ).addClass( 'is-' + state );
	if ( state === 'done' ) {
		status.closest( '.ec-atc-bulk-label' ).find( '.ec-auto-tag-progress span' ).css( 'width', '100%' );
	}
	jQuery( '.ec-auto-tag-bulk' ).prop( 'disabled', false );
}

function ec_auto_tag_bulk_run( button, status, offset ) {
	jQuery.post( ajaxurl, {
		action: 'ec_auto_tag_bulk_create',
		security: ec_auto_tag_creator.security,
		post_type: button.data( 'post-type' ),
		offset: offset
	} ).done( function( response ) {
		if ( ! response || ! response.success ) {
			ec_auto_tag_bulk_finish( status, 'error', ( response && response.data && response.data.msg ) || ec_auto_tag_creator.error );
			return;
		}
		var data = response.data;
		status.closest( '.ec-atc-bulk-label' ).find( '.ec-auto-tag-progress span' ).css( 'width', ( data.total ? Math.round( data.processed / data.total * 100 ) : 100 ) + '%' );
		if ( data.done ) {
			ec_auto_tag_bulk_finish( status, 'done', ec_auto_tag_format( data.processed === 1 ? ec_auto_tag_creator.done_single : ec_auto_tag_creator.done, data.processed ) );
			return;
		}
		status.text( ec_auto_tag_format( ec_auto_tag_creator.processing, data.processed, data.total ) );
		ec_auto_tag_bulk_run( button, status, data.processed );
	} ).fail( function() {
		ec_auto_tag_bulk_finish( status, 'error', ec_auto_tag_creator.error );
	} );
}

jQuery( function( $ ) {
	var modal = $( '#ec-atc-bulk-modal' );
	var activeButton = null;

	function openModal( button ) {
		activeButton = button;
		modal.find( '#ec-atc-modal-title' ).text( button.data( 'title' ) );
		modal.prop( 'hidden', false );
		$( 'body' ).addClass( 'ec-atc-modal-open' );
		modal.find( '#ec-atc-modal-confirm' ).trigger( 'focus' );
	}

	function closeModal() {
		modal.prop( 'hidden', true );
		$( 'body' ).removeClass( 'ec-atc-modal-open' );
		if ( activeButton ) {
			activeButton.trigger( 'focus' );
		}
	}

	function startBulk( button ) {
		var label = button.siblings( '.ec-atc-bulk-label' );
		var status = label.find( '.ec-auto-tag-bulk-status' );
		label.removeClass( 'is-done is-error' );
		label.find( '.ec-auto-tag-progress' ).prop( 'hidden', false ).find( 'span' ).css( 'width', 0 );
		$( '.ec-auto-tag-bulk' ).prop( 'disabled', true );
		status.text( ec_auto_tag_format( ec_auto_tag_creator.processing, 0, '...' ) );
		ec_auto_tag_bulk_run( button, status, 0 );
	}

	$( '.ec-auto-tag-bulk' ).on( 'click', function() {
		openModal( $( this ) );
	} );

	modal.on( 'click', '[data-close]', closeModal );

	modal.on( 'click', '#ec-atc-modal-confirm', function() {
		var button = activeButton;
		closeModal();
		startBulk( button );
	} );

	$( document ).on( 'keydown', function( e ) {
		if ( modal.prop( 'hidden' ) ) {
			return;
		}
		if ( e.key === 'Escape' ) {
			closeModal();
		} else if ( e.key === 'Tab' ) {
			var focusable = modal.find( 'button:visible' );
			var first = focusable.first()[0];
			var last = focusable.last()[0];
			if ( e.shiftKey && document.activeElement === first ) {
				e.preventDefault();
				last.focus();
			} else if ( ! e.shiftKey && document.activeElement === last ) {
				e.preventDefault();
				first.focus();
			}
		}
	} );
} );
