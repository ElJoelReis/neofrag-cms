<textarea class="form-control editor" name="settings[content]" rows="6"><?php echo $data['content']; ?></textarea>
<script type="text/javascript" src="{js jquery.wysibb.min.js}"></script>
<script type="text/javascript" src="{js jquery.wysibb.fr.js}"></script>
<script type="text/javascript">
	$(function(){
		$('<link rel="stylesheet" href="{css wbbtheme.css}" type="text/css" media="screen" />').appendTo('head');
		$('#live-editor-settings-form textarea.editor').wysibb({lang: "fr"});
		
		$('#live-editor-settings-form').on('nf.live-editor-settings.submit', function(){
			if ($('#live-editor-settings-form textarea.editor').length){
				$('#live-editor-settings-form textarea.editor').sync();
			}
		});
	});
</script>