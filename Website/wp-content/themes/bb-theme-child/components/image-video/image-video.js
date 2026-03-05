jQuery(document).ready(function ($) {
    console.log('_constructor.js ready');
    // Background video modal //
    function bg_video_modal(){
        $(".column_videobg").each(function(){
            
            if ($(this).find(".video_window_wrapper_inner").length)  {
                wrapper_h = $(this).find(".video_window_wrapper").height()
                wrapper_w = $(this).find(".video_window_wrapper").width()

                if (!$(this).find(".fl-module-modal-popup").length)
                    $(this).find(".video_window_wrapper").css({
                        "min-height" : wrapper_h-90
                    })

                $(this).find(".video_window_wrapper_inner").height(wrapper_h)
                
                new_w = wrapper_w+wrapper_h*3
                $(this).find(".video_window_wrapper_inner").width(new_w)
                
                $(this).find(".video_window_wrapper_inner").css("left",-(new_w/2 - wrapper_h/1.2))
                //$(this).find(".video_window_wrapper_inner").css("top",-wrapper_h/8)
            }            
        })


    }
    bg_video_modal();

    // WINDOW RESIZE //
    $(window).on( "resize", function() {
        bg_video_modal();
    });
    // WINDOW SCROLL //
    $(window).on( "scroll", function() {

    });
})