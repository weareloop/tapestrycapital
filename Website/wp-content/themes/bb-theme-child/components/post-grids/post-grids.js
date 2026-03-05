jQuery(document).ready(function ($) {
    console.log('post-grids.js ready');
    // Post Grid + Filter //
    /*************/
    /* Post Ajax */
    /*************/
    $('.cat-list_item').on('click', function() {
        $('.cat-list_item').removeClass('active');
        $(this).addClass('active');
        //console.log($(this).data('slug'));
        $.ajax({
          type: 'POST',
          url: '/wp-admin/admin-ajax.php',
          dataType: 'html',
          data: {
            action: 'filter_projects',
            category: $(this).data('slug'),
          },
          success: function(res) {
            $('.project-tiles').html(res);
            console.log(res);
          }
        })
    });
    $('.category-filters--go button').on('click', function(event) {
        event.preventDefault();
        var catString = '';
        var txtString = '';
        $(this).parents('.filter-form--content').find("input:checked").each(function( index ) {
            //console.log($( this ).attr("value") );
            //console.log($( this ).attr("data-taxonomy") );
            catString += $( this ).attr("data-taxonomy") + ":" + $( this ).attr("value") + "&";
        });
        var gridType = '';
        if($(".posts").hasClass("list")){
            gridType = "list";
        }else if($(".posts").hasClass("card")){
            gridType = "card";
        }else{
            gridType = "grid";
        }
        console.log("==>"+gridType);
        var readmore = $(".posts-container").data('readmore');
        var numberperpage = $(".posts-container").data('numberperpage');
        var postType = $(".posts-container").data('post');
        txtString = "text" + ":" + $(this).parents('.filter-form--content').find("input[type=text]").val();
        $.ajax({
            type: 'POST',
            url: '/wp-admin/admin-ajax.php',
            dataType: 'html',
            data: {
              action: 'filter_projects',
              category: catString,
              text: txtString,
              grid: gridType,
              postType: postType,
              numberperpage: numberperpage,
              readmore: readmore
            },
            beforeSend: function() {
                $('.posts-container').css("opacity", 0);
                $('.posts-container').css("max-height","0px");

                $('.loading').css("display", "block").delay( 500 ).queue(function(next){
                    $('.loading').css("opacity","1");
                    next();
                });
                $(".category-filters--go button").addClass("disable");
                $(".filter_taxo_item_input").prop("disabled", true);
            },
            complete:function() {
                $('.loading').css({
                    "opacity":"0",
                    "height":"0",
                    "display":"none"
                });
              },
            success: function(res) {
                console.log("success");
              $(".category-filters--go button").removeClass("disable");
              $('.posts-container').css("max-height","5000px");
              $('.posts-container').html(res);
              $('.posts-container').css("opacity", 1);
              $(".filter_taxo_item_input").prop("disabled", false);
              $(".loadMore").each(
                function(){
                    if($(this).siblings(".posts").children(".active").length==$(this).siblings(".posts").children().length){
                        $(this).hide();
                    }
                }
            );
              window.history.pushState("string1", "Title", window.location.pathname+'?'+catString+txtString);
            }
          })
    });
    $('.postsFilterForm input').on('change', function(event) {
        event.preventDefault();
        var catString = '';
        var txtString = '';
        $(this).parents('.filter-form--content').find("input:checked").each(function( index ) {
            //console.log($( this ).attr("value") );
            //console.log($( this ).attr("data-taxonomy") );
            catString += $( this ).attr("data-taxonomy") + ":" + $( this ).attr("value") + "&";
        });
        var gridType = '';
        if($(".posts").hasClass("list")){
            gridType = "list";
        }else if($(".posts").hasClass("card")){
            gridType = "card";
        }else{
            gridType = "grid";
        }
        
        var readmore = $(".posts-container").data('readmore');
        var numberperpage = $(".posts-container").data('numberperpage');
        var postType = $(".posts-container").data('post');
        txtString = "text" + ":" + $(this).parents('.filter-form--content').find("input[type=text]").val();

        $.ajax({
            type: 'POST',
            url: '/wp-admin/admin-ajax.php',
            dataType: 'html',
            data: {
              action: 'filter_projects',
              category: catString,
              text: txtString,
              grid: gridType,
              postType: postType,
              numberperpage: numberperpage,
              readmore: readmore
            },
            beforeSend: function() {
                $('.posts-container').css("opacity", 0);
                $('.posts-container').css("max-height","0px");
                $('.loading').css("display", "block").delay( 500 ).queue(function(next){
                    $('.loading').css("opacity","1");
                    next();
                });
                $(".category-filters--go button").addClass("disable");
                $(".filter_taxo_item_input").prop("disabled", true);
            },
            complete:function() {
                $('.loading').css({
                    "opacity":"0",
                    "height":"0",
                    "display":"none"
                });
              },
            success: function(res) {
                console.log("success");
              $(".category-filters--go button").removeClass("disable");
              $('.posts-container').css("max-height","5000px");
              $('.posts-container').html(res);
              $('.posts-container').css("opacity", 1);
              $(".filter_taxo_item_input").prop("disabled", false);
              $(".loadMore").each(
                function(){
                    if($(this).siblings(".posts").children(".active").length==$(this).siblings(".posts").children().length){
                        $(this).hide();
                    }
                }
            );
              window.history.pushState("string1", "Title", window.location.pathname+'?'+catString+txtString);
            }
        })
    });

    ///////////////////////////
    // Toggle Filter Options //
    ///////////////////////////
    
    function filter_toggle() {
        $('.fl-archive--filter-refine').find('svg').toggleClass("active");
        if($('.filter-form--content').hasClass('hidden')){
            $('.filter-form--content').toggleClass('hidden');
            setTimeout(function(){$('.filter-form--content').toggleClass('visible')},20);
        }else{
            $('.filter-form--content').toggleClass('visible');
            setTimeout(function(){$('.filter-form--content').toggleClass('hidden')},520);
        }
        $(".fl-archive--filter").toggleClass("closed");
    }
    
    $('.fl-archive--filter-refine').on({
        click : function() {filter_toggle()},
        keypress : function(e) {if (e.which == 13) filter_toggle()}
        
    });

    $(document).on('keypress',function(e) {
        if(e.which == 13) {
            if ($(".fl-archive--filter-search input").is(":focus")) {
                e.preventDefault();
                $(".category-filters--go button").trigger("click")
            }
        }
    });

    $(document).on('keypress',function(e) {
        if(e.which == 13) {
            if ($(".fl-archive--filter-items ul li label").is(":focus")) {
                e.preventDefault();
                $(".fl-archive--filter-items ul li label:focus input").trigger("click")
            }
        }
    });

    function filter_items_toggle(che) {
        if(che.siblings().hasClass('visible')){
            che.parent().toggleClass('visible');
            setTimeout(function(){che.siblings().toggleClass('visible')},20);
        }else{
            che.siblings().toggleClass('visible');
            setTimeout(function(){che.parent().toggleClass('visible')},20);
        }
    }
    
    $('.unfoldable .filter-item--label-title').on({
        click : function() {filter_items_toggle($(this))},
        keypress : function(e) {if (e.which == 13) filter_items_toggle($(this))}
    }); 

   /*
    $(".filter_taxo_item_input").on("click", function(){
        $(this).parents(".filter_taxo_item").toggleClass("active");
    })
    */

    //Load more Btn
    $("body").on('click', ".loadMore_btn", function (e) {
        var perpage = $(this).attr("class").split("perpage_")[1].split(" ")[0];
        
        $(this).parents(".posts-container").find(".post:not(.active)").each(function(){
            if(perpage>0){
                $(this).addClass("active").delay(50).queue(function(next){$('.post.active').addClass('visual');next();})
            }
            perpage--;
        });
        if($(this).parent().siblings(".posts-list, .posts").children(".active").length==$(this).parent().siblings(".posts-list, .posts").children().length){
            $(this).parent().hide();
        }
    });

    //Load more Btn hidden on fully loaded
    $(".loadMore").each(
        function(){
            if($(this).siblings(".posts-list, .posts").children(".active").length==$(this).siblings(".posts-list, .posts").children().length){
                $(this).hide();
            }
        }
    );
    //page button clickable
    $("body").on("click", ".page_btn.list",
        function () {
            $(this).parent().siblings().children(".post-list.active").removeClass("active");
            $(this).parent().siblings().children(".post-list.page_" + $(this).text()).addClass("active");
            $(".page_btn").removeClass("active");
            $(this).addClass("active");
        }
    );

    $("body").on("click", ".page_btn:not(.list)",
        function () {
            $(this).parent().siblings().children(".post.active").removeClass("active");
            $(this).parent().siblings().children(".post.page_" + $(this).text()).addClass("active");
            $(".page_btn").removeClass("active");
            $(this).addClass("active");
        }
    );

    // Post Grid + Filter //
    // Clear Updates Filter Selection (Resources)
        $('.fl-archive--filter-clear span').click(function() {
            $('.postsFilterForm input:checkbox').prop("checked", false);
            $('.postsFilterForm input:text').val("");
            setTimeout(() => {
                $('.category-filters--go button').click();
            }, 1000);
            
            //window.location.href=window.location.origin+window.location.pathname+'?clearAll=true';
    });
    $('.fl-archive--filter-clear span').on("keypress",function (e) {
            if (e.which == 13) {
                $('.postsFilterForm input:checkbox').prop("checked", false);
                $('.postsFilterForm input:text').val("");
                setTimeout(() => {
                    $('.category-filters--go button').trigger("click");
                }, 500);
            }
    });




    // Search Results //
    $(".search_results_item .fl-post-image").on("click",function(){
        location.href = $(this).parent().find(".fl-post-text .fl-post-title-link").attr("href");
    })














    ///////////////////
    // WINDOW RESIZE //
    ///////////////////
    $(window).on( "resize", function() {
        
    });


    ///////////////////
    // WINDOW SCROLL //
    ///////////////////
    $(window).on( "scroll", function() {
    
    });
})