(function($) {
	$(window).scroll(function() {
		$('body').addClass('is-scrolling');
		clearTimeout($.data(this, 'scrollTimer'));
		$.data(this, 'scrollTimer', setTimeout(function() {
			$('body').removeClass('is-scrolling');
		}, 100));
	});
})(jQuery);
