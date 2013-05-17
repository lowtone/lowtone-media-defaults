$ = jQuery

$ ->
	$('input[name="lowtone_media_defaults[default]"]').click ->
		return if !(typeof wp != 'undefined' && wp.media && wp.media.editor)
		
		wp.editor.open('lowtone_media_defaults') 

		original_send = wp.media.editor.send.attachment

		wp.media.editor.send.attachment = (a, b) ->
			console.log b

		window.original_send_to_editor = window.send_to_editor

		window.send_to_editor = (html) ->
			console.log html