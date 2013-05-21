$ = jQuery

$ ->
	media_frame = null

	$('input[name="lowtone_media_defaults[default]"]').click ->
		$input = $ this

		if media_frame
			media_frame.open()
			return

		media_frame = wp.media 
			className: 'media-frame lowtone-media-dropbox'
			frame: 'select'
			multiple: false
			title: lowtone_media_defaults.title
			library: 
				type: 'image'
			button:
				text: lowtone_media_defaults.button_text

		media_frame.on 'select', ->
			attachment = media_frame.state().get('selection').first().toJSON()

			$input.val attachment.id

		media_frame.open()