<script type="text/javascript">
//scroll lock plugin

	(function (e) {
	    "use strict";

	    function i(t, n) {
	        this.opts = e.extend({
	            handleKeys: !0,
	            scrollEventKeys: [32, 33, 34, 35, 36, 37, 38, 39, 40]
	        }, n);
	        this.$container = t;
	        this.$document = e(document);
	        this.disable()
	    }
	    var t, n = function (e) {
	        for (var t = 0; t < this.opts.scrollEventKeys.length; t++) if (e.keyCode === this.opts.scrollEventKeys[t]) {
	            e.preventDefault();
	            return
	        }
	    }, r = function (e) {
	        e.preventDefault()
	    };
	    i.prototype = {
	        disable: function () {
	            var e = this;
	            e.$container.on("mousewheel.UserScrollDisabler DOMMouseScroll.UserScrollDisabler touchmove.UserScrollDisabler", r);
	            e.opts.handleKeys && e.$document.on("keydown.UserScrollDisabler", function (t) {
	                n.call(e, t)
	            })
	        },
	        undo: function () {
	            var e = this;
	            e.$container.off(".UserScrollDisabler");
	            e.opts.handleKeys && e.$document.off(".UserScrollDisabler")
	        }
	    };
	    e.fn.disablescroll = function (e) {
	        !t && (typeof e == "object" || !e) ? t = new i(this, e) : t && t[e] ? t[e].call(t) : t && t.disable.call(t)
	    }
	})(jQuery);


	var $nonScrollable = $(document);

//user

	$( document ).ready(function() {

		//menu
        $('.menu_button').click(function() {
			$('.menu').fadeIn();
			$(this).fadeOut();
			$('.menu_close').fadeIn();
			//$( document.body ).css( "overflow-y", "hidden" );
			$nonScrollable.disablescroll();
		});
		
		$('.menu_close').click(function() {
			$('.menu').fadeOut();
			$(this).fadeOut();
			$('.menu_button').fadeIn();
			//$( document.body ).css( "overflow-y", "scroll" );
			$nonScrollable.disablescroll("undo");
		});

		//fancybox-gallery
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

<script type="text/javascript">
var version = navigator.appVersion;
alert(version);

</script>






