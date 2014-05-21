$(document).ready(function(){
	
    $('.myMenu > li').bind('mouseover', openSubMenu);
    $('.myMenu > li').bind('mouseout', closeSubMenu);

    function openSubMenu() {
        $(this).find('ul').css('visibility', 'visible');
        $(this).find('ul').addClass('plashka');
        $('#contact_top').css('visibility', 'hidden');
        
    };

    function closeSubMenu() {

        $('#contact_top').css('visibility', 'visible');
        $(this).find('ul').css('visibility', 'hidden');
    };


    
});

