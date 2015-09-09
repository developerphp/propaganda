<!--<script src="<?php echo base_url() ?>assets/js/blueimp-gallery.min.js"></script>-->
<!--<script>
document.getElementById('links').onclick = function (event) {
    event = event || window.event;
    var target = event.target || event.srcElement,
        link = target.src ? target.parentNode : target,
        options = {index: link, event: event},
        links = this.getElementsByTagName('a');
    blueimp.Gallery(links, options);
};
</script>-->
    
<script type="text/javascript">

	$( document ).ready(function() {
        $('.menu_button').click(function() {
			$('.menu').fadeIn();
			$(this).fadeOut();
			$('.menu_close').fadeIn();
			$( document.body ).css( "overflow-y", "hidden" );
		});
		
		$('.menu_close').click(function() {
			$('.menu').fadeOut();
			$(this).fadeOut();
			$('.menu_button').fadeIn();
			$( document.body ).css( "overflow-y", "scroll" );
		});

		$('.fancybox').fancybox();

		$('.fancybox-media')
				.attr('rel', 'media-gallery')
				.fancybox({
					openEffect : 'none',
					closeEffect : 'none',
					prevEffect : 'none',
					nextEffect : 'none',

					arrows : false,
					helpers : {
						media : {},
						buttons : {}
					}
				});


    });
	
</script>

