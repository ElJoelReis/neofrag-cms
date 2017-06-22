$(function(){
	$(document).on('click', '[data-modal-ajax]', function(e){
		var $this = $(this);

		if ($this.is('a')) e.preventDefault();

		if (typeof $this.data('modal') == 'undefined'){
			$.get($this.data('modal-ajax'), function(data){
				var promises = [];

				if (typeof data.css != 'undefined'){
					$('head').append(data.css);
				}

				if (typeof data.js != 'undefined'){
					$.each(data.js, function(_, js){
						promises.push($.getScript(js));
					});
				}

				$.when.apply($, promises).done(function(){
					var $modal = $(data.content).appendTo('body').closest('.modal');

					$modal.find('.modal-footer [type="submit"]').click(function(e){
						e.preventDefault();

						var $form = $modal.find('form');

						$.post($form.attr('action'), $form.serialize(), function(data){
							if (typeof data.success != 'undefined' && data.success == 'refresh'){
								location.reload();
							}
							else if (typeof data.form != 'undefined'){
								$modal.find('.modal-body').html(data.form);
								$('body').trigger('nf.load');
							}
						});
					});

					$('body').trigger('nf.load');

					$this.data('modal', $modal.modal());
				});
			});
		}
		else {
			$this.data('modal').modal();
		}
	});
});
