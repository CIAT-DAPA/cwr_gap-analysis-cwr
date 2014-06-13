jQuery(document).ready(function($) {
    
    // Tool tip for herbaria data
    $(".herbariaToolTip").poshytip({
        className: 'tip-green',
        offsetX: -7,
        offsetY: 16,
        allowTipHover: true
    });    


    $(".more, .minus").click(function(){
        var $box = $(this).parent();
        var $next = $box.find(".box-content");
        var $minus = $box.find(".minus");
        var $more = $box.find(".more");
        // if more is showing
        if($more.css("display") != "none") {
            $next.slideDown("fast");
            $more.hide();
            $minus.show();
        } else {
            $next.slideUp("fast");
            $minus.hide();
            $more.show();
        }
    });
    
    // This method should be at the end of this file.
    $(".more").each(function(index, element) {  
        var $box = $(element).parent();
        var $next = $box.find(".box-content");
        var $minus = $box.find(".minus");
        var $more = $box.find(".more");
        $next.slideDown("fast");
        $more.hide();
        $minus.show();
    });
});