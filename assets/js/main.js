
$(function() {

	$(window).scroll(function(){
		if ( $(window).scrollTop()>400 && $(window).width() > 900 ){
			$('#scrolltop').css({bottom:100}).show();
		}else{
			$('#scrolltop').css({bottom:80}).hide();
		}
	});
	
	$( '#scrolltop' ).click(function(){
		$('html,body').stop().animate({scrollTop:0},400);
	});

});