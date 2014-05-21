$(function() {
    // $('a.lightbox').lightBox();
    //hover states on the static widgets
    $('a.ui-state-default').hover(
        function() {
            $(this).addClass('ui-state-hover');
        },
        function() {
            $(this).removeClass('ui-state-hover');
        }
        );
            

	$(".lightbox").lightBox();
	

            
});