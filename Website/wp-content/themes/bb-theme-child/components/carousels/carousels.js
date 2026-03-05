jQuery(document).ready(function ($) {
    console.log('carousels.js ready');
    
    /*************************************************/
    /* Carousel - Testimonial - Featured Image Cards */
    /*************************************************/

    $("body:not(.fl-builder-edit) .action-carousel-row:not(.img-feature) .action-carousel-slider .fl-col-content").each(function(){
        //console.log("action carousel running");
        $(this).slick({
        dots: true,
        infinite: true,
        speed: 300,
        adaptiveHeight: false,
        variableWidth: true,
        draggable:false,
        appendDots: $(this).parents('.action-carousel-row').find(".slider-dot"),
        appendArrows: $(this).parents('.action-carousel-row').find(".slider-ctl"),
        prevArrow:'<button type="button" class="slick-prev hidefocus"><img src="/wp-content/uploads/circle-arrow-left-red.svg" alt="arrow left"></button>',
        nextArrow:'<button type="button" class="slick-next hidefocus"><img src="/wp-content/uploads/circle-arrow-right-red.svg" alt="arrow right"></button>',
        responsive: [
            {
            breakpoint: 769,
            settings: {
                draggable:false,
            }
            }
        ]
        });
    });

    $("body:not(.fl-builder-edit) .action-carousel-row.img-feature .action-carousel-slider .fl-col-content").each(function(){
        //console.log("action carousel running");
        $(this).slick({
        dots: true,
        infinite: true,
        speed: 300,
        adaptiveHeight: false,
        variableWidth: true,
        draggable:false,
        appendDots: $(this).parents('.action-carousel-row').find(".slider-dot"),
        appendArrows: $(this).parents('.action-carousel-row').find(".slider-ctl"),
        prevArrow:'<button type="button" class="slick-prev hidefocus"><img src="/wp-content/uploads/circle-arrow-left-white.svg" alt="arrow left"></button>',
        nextArrow:'<button type="button" class="slick-next hidefocus"><img src="/wp-content/uploads/circle-arrow-right-white.svg" alt="arrow right"></button>',
        responsive: [
            {
            breakpoint: 769,
            settings: {
                draggable:false,
            }
            }
        ]
        });
    });

    /**********************************************/
    /* Carousel - Testimonial - Overlapping Cards */
    /**********************************************/

    $("body:not(.fl-builder-edit) .overlap-carousel-img-col .fl-col-content").each(function(){
        var asNav = $(this).parents('.overlap-carousel-row').find('.overlap-carousel-info-col .fl-col-content');
        $(this).slick({
        dots: true,
        infinite: true,
        speed: 300,
        adaptiveHeight: false,
        fade: true,
        slidesToShow: 1,
        draggable:false,
        asNavFor: asNav,
        appendDots: $(this).parents('.overlap-carousel-row').find(".slider-dot"),
        appendArrows: $(this).parents('.overlap-carousel-row').find(".slider-ctl"),
        prevArrow:'<button type="button" class="slick-prev hidefocus"><img src="/wp-content/uploads/Group-11193.svg" alt="arrow left"></button>',
        nextArrow:'<button type="button" class="slick-next hidefocus"><img src="/wp-content/uploads/Group-11194.svg" alt="arrow right"></button>',
        responsive: [
            {
            breakpoint: 769,
            settings: {
                //draggable:false,
            }
            }
        ]
        });
    });
    $("body:not(.fl-builder-edit) .overlap-carousel-info-col .fl-col-content").each(function(){
        var asNav = $(this).parents('.overlap-carousel-row').find('.overlap-carousel-img-col .fl-col-content');
        $(this).slick({
        dots: false,
        arrows: false,
        infinite: true,
        adaptiveHeight: false,
        fade: false,
        slidesToShow: 1,
        draggable:false,
        asNavFor: asNav,
        responsive: [
            {
            breakpoint: 769,
            settings: {
                adaptiveHeight:true
            }
            }
        ]
        });
    });
    //Carousel - Dots change label
    setTimeout(function(){ $(".slick-dots").find("button").each(
        function() {
            $(this).text($(this).attr("aria-label"));
            //replace the 'of' for any other language
        }
    ) }, 100);

    /*****************************/
    /*  START :: Marquee Ticker  */
    /*****************************/
    const marquees = document.querySelectorAll("body:not(.fl-builder-edit) .marquee");
    if (!window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
        // console.log('user does NOT prefer reduced motion, proceed with animation');
        initMarquee();
    }
    else {
        // console.log('user DOES prefer reduced motion, do NOT proceed with animation');
    }
    function initMarquee() {
        marquees.forEach(marquee => {
            // data-animated=loading needs to be set initially to calculate marqueeContentWidth
            marquee.setAttribute("data-animated", 'loading');
            const marqueeWidth = marquee.offsetWidth;

            const marqueeInner = marquee.querySelector(".fl-col-content");
            const marqueeContentWidth = marqueeInner.offsetWidth;
            // console.log('marqueeContentWidth', marqueeContentWidth, 'marqueeWidth', marqueeWidth);

            if (marqueeContentWidth > marqueeWidth) {
                // if marqueeContentWidth is wide enough for the carousel to not be buggy 
                let marqueeContent = Array.from(marqueeInner.children);

                marqueeContent.forEach((item) => {
                    const duplicatedItem = item.cloneNode(true);
                    // hide duplicated children from screen readers
                    duplicatedItem.setAttribute('aria-hidden', true);
                    marqueeInner.appendChild(duplicatedItem);
                });

                // data-animated=true needs to be set now for animation
                marquee.setAttribute("data-animated", true);
            }
            else {
                // marqueeContentWidth is not wide enough to support carousel, therefore disable
                marquee.setAttribute("data-animated", false);
            }
            
        });
    }
    /*****************************/
    /*  END   :: Marquee Ticker  */
    /*****************************/
    /******************/
    /* VIdeo Carousel */
    /******************/

    $("body:not(.fl-builder-edit) .video-carousel-slider .fl-col-content").each(function(){
		$(this).slick({
		  dots: true,
		  infinite: true,
        	speed: 300,
			variableWidth: true,
        	slidesToScroll: 1,
			slidesToShow: 1,
        	centerMode: false,
            variableWidth: true,
        	adaptiveHeight: false,
			centerMode: true,
			draggable:true,
        	centerPadding: '0px',
		  	appendDots: $(this).parents('.video-carousel-row').find(".slider-dot"),
		  	appendArrows: $(this).parents('.video-carousel-row').find(".slider-ctl"),
		  	prevArrow:'<button type="button" class="slick-prev hidefocus"><img src="/wp-content/uploads/Group-11193.svg" alt="arrow left"></button>',
            nextArrow:'<button type="button" class="slick-next hidefocus"><img src="/wp-content/uploads/Group-11194.svg" alt="arrow right"></button>',
            responsive: [
				{
				breakpoint: 993,
					settings: {
						draggable:true,
						variableWidth: true,
                		slidesToShow: 1,
                		slidesToScroll: 1,
						centerPadding: '0px'
					}
				}
		  	]
		});
	});
})