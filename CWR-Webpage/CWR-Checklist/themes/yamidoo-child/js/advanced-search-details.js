jQuery(document).ready(function($) {
    
    $('#count-box').animate({
         right: '0px'
    }, 1000, function() {
        $('#taxa-list ul').slideDown('fast');
    });    
    
    $("#download-box").animate({
        
    }, 1000);
    
});