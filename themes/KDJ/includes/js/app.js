$=jQuery;
//$( "div a:contains('trial version')" ).css( "display", "none" );

// $('a:contains()
$('a:contains("trial version")').remove();
$('span a:contains("trial version")').remove();
$('a:contains("trial version")').css({'z-index': '-1'});
$('span a:contains("trial version")').css({'z-index': '-1'});

$(document).ready(function(){
//     $("a:contains(Magic Scroll™ trial version)").remove();
    
    $('a:contains("trial version")').css({'z-index': '-1'});
$('span a:contains("trial version")').css({'z-index': '-1'});
    
$('a:contains("trial version")').remove();
$('span a:contains("trial version")').remove();

/*
    let thumb = $(this).find("a:contains(Magic Scroll™ trial version)").attr('href');
    console.log($(this).find("a:contains(Magic Scroll™ trial version)").attr('href'));
*/
});