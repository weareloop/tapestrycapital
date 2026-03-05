<?php

///////////////////////////
// Get Image ID from URL //
///////////////////////////
function getImageIdByUrl( $url )
{
    global $wpdb;
    // If the URL is auto-generated thumbnail, remove the sizes and get the URL of the original image
    $url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $url );
    $image = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url ));
    if(!empty($image)) { return $image[0]; }
    return false;
}

///////////////////////////
//Video Background Modal //
///////////////////////////

function bg_video_modal($atts){
	
	$atts = shortcode_atts( array(
		'youtube_id' => '',
        'poster_img' => '',
	), $atts, 'bg_video_modal' );
	$youtube_id =  $atts['youtube_id'] ?: '';
	$poster_img =  $atts['poster_img'] ?: '';
    if ($poster_img) {
        $img_id = getImageIdByUrl($poster_img);
        $poster_style = ' style="background:url('.wp_get_attachment_image_url($img_id,"medium").') center center / cover no-repeat;" ';
    }

	ob_start();
    ?>
    <div class="video_window_wrapper" <?=$poster_style;?>>
        <?php if ($youtube_id) { ?>
            <div class="video_window_wrapper_inner">
                <iframe class="video_window" width="100%" height="100%" src="https://www.youtube.com/embed/<?=$youtube_id;?>?autoplay=1&mute=1&playsinline=0&amp;controls=0&amp;showinfo=0&amp;rel=0&amp;start=0&amp;end=0&amp;enablejsapi=1&amp;widgetid=1&amp;loop=1&amp;playlist=<?=$youtube_id;?>"></iframe>
            </div>
        <? } ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'bg_video_modal', 'bg_video_modal' );
