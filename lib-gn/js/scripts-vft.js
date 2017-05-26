jQuery(document).ready(function(){
	var lienTaxo = jQuery('.taxo-wordcampeur a');
	lienTaxo.addClass('btn btn-default btn-xs');
	jQuery('.breadcrumb.taxo-wordcampeur').css({
		'line-height' : '31px'
	});
	jQuery('img').parent('a').css('border','none');
}); // document ready