(function($) {
	$(function() {

		$(window).resize(function() {

			var headerHeight = $("header").outerHeight();
			var bartopHeight = $(".bar-top").outerHeight();
			var windowWidth = $(window).width();
			var windowHeight = $(window).height();
			$('.row-table').css('min-height', (windowHeight - (headerHeight + bartopHeight)) + 'px');

			var peopleHeight = $(".people-list .people").height();
			$('.people-list .more a').css('height', peopleHeight + 'px');

			$('.modal').css({
				'width' : windowWidth + 'px',
				'height' : windowHeight + 'px'
			});

		});

		$('.dropdown-toggle').dropdown();

		$(window).resize().scroll();
	});

	$(window).load(function() {
		$(window).resize().scroll();
	});
})(jQuery);

/*
 * Extended functionality by Juanpablob
 *
 */

var extend = {
	triggers: function() {
		/* Tabs */
		$('.profile-nav a').click(function(e) {
			e.preventDefault();

			var destination = $(this).attr('href');
			$('.profile-pane').hide();
			$(destination).show();
			$(this).parent().find('a').removeClass('active');
			$(this).parent().find('a[href="' + destination + '"]').addClass('active');
		});

		/* Read more (Short profile) */
		$('.short-profile a').click(function(e) {
			e.preventDefault();

			$('.profile-pane').hide();
			$('#profile-profile').show();
			$('.profile-nav a').removeClass('active');
			$('.profile-nav').find('a[href="#profile-profile"]').addClass('active');
		})
	},

	ui: function() {
		/* Default clicks */
		$('a[href="#"]').click(function(e) {
			e.preventDefault();
		});

		/* Top bar */
		$('.bar-top a').attr('target', '_blank');

		$('[data-toggle="tooltip"]').tooltip();
	},

	init: function() {
		extend.triggers();
		extend.ui();
	}
};

$(document).ready(function() {
	extend.init();
});
