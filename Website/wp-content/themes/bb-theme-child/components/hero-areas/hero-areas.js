jQuery(document).ready(function ($) {
    console.log('hero-areas.js ready');
    /******************/
    /* Hero VIDEO */
    /******************/

    if ($(".youtube_pause").length) {
        initBackgroundVideoControls();
    }

    function initBackgroundVideoControls() {
        const pauseText = `Pause Video`;
        const playText = `Play Video`;
        // Pause Button HTML template
        const pauseButtonHTML = `
            <div class="youtube_controls">
                <button id="yt-pause-button" class="yt-control-button yt-pause-button hidefocus" aria-label="Pause background video" aria-pressed="true">${pauseText}</button>
            </div>
        `;

        function checkForLoadedVideos(attempts = 0) {
            if (attempts > 30) {
                return;
            }

            let allLoaded = true;
            
            $('.fl-bg-video').each(function () {
                const $container = $(this);
                const rowVideo = $(this).parents('.youtube_pause');
                if (!rowVideo.find('.youtube_controls').length) {
                    if ($container.data('loaded')) {
                        rowVideo.find('.youtube_controls_wrapper').append(pauseButtonHTML);
                        initVideoControls($container);
                    } else {
                        allLoaded = false;
                    }
                }
            });

            // If not all videos are loaded, check again in 500ms
            if (!allLoaded) {
                setTimeout(() => checkForLoadedVideos(attempts + 1), 500);
            }
            // all videos are loaded!
            else {
                $("#yt-pause-button").each(function () { 
                    // if not mobile with video disabled, show video controls
                    if ($(this).parents('.youtube_pause').find('fl-bg-video-fallback')) {
                        $(this).addClass('active');
                    }
                });

                // wait until videos are loaded to start the 5s accessibility timer
                yt_pause = setInterval(function(){
                    $(".fl-bg-video-player").each(function () {
                        var iframe = $( this );
                        if ($('.fl-bg-video-player').length) {
                            iframe.get(0).contentWindow.postMessage('{"event":"command","func":"' + 'pauseVideo' + '","args":""}', '*');
                            iframe.parents(".youtube_pause").find(".yt-pause-button").trigger("click");
                        }
                    });
                },5000)

                setTimeout(function(){
                    clearInterval(yt_pause);
                }, 5001)
            }
        }
        // Start checking for loaded videos
        checkForLoadedVideos();

        function initVideoControls($container) {
            const $button = $container.parent().find('#yt-pause-button');

            // Self-hosted video
            const $video = $container.find('video');
            if ($video.length) {
                $button.on('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if ($video[0].paused) {
                        $video[0].play();
                        $(this)
                            .removeClass('active yt-play-button')
                            .html(pauseText)
                            .addClass('yt-pause-button')
                            .attr({
                                'aria-label': 'Pause background video',
                                'aria-pressed': true,
                            })
                            .delay(150).queue(function (next) {
                                // wait before adding active class, for animation
                                $(this).addClass('active');
                                next();
                            });
                    } else {
                        $video[0].pause();
                        $(this)
                            .removeClass('active yt-pause-button')
                            .html(playText)
                            .addClass('yt-play-button')
                            .attr({
                                'aria-label': 'Play background video',
                                'aria-pressed': false,
                            })
                            .delay(150).queue(function (next) {
                                // wait before adding active class, for animation
                                $(this).addClass('active');
                                next();
                            });
                    }
                });
                return;
            }

            // YouTube video
            if ($container.data('youtube')) {
                function checkForPlayer() {
                    const $iframe = $container.find('iframe.fl-bg-video-player');
                    const player = YT.get($iframe.attr('id'));

                    if (!player) {
                        setTimeout(checkForPlayer, 500);
                        return;
                    }

                    $button.on('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const state = player.getPlayerState();
                        if (state === YT.PlayerState.PLAYING) {
                            player.pauseVideo();
                            $(this)
                                .removeClass('active yt-pause-button')
                                .html(playText)
                                .addClass('yt-play-button')
                                .attr({
                                    'aria-label': 'Play background video',
                                    'aria-pressed': false,
                                })
                                .delay(150).queue(function (next) {
                                    // wait before adding active class, for animation
                                    $(this).addClass('active');
                                    next();
                                });
                        } else {
                            player.playVideo();
                            $(this)
                                .removeClass('active yt-play-button')
                                .html(pauseText)
                                .addClass('yt-pause-button')
                                .attr({
                                    'aria-label': 'Pause background video',
                                    'aria-pressed': true,
                                })
                                .delay(150).queue(function (next) {
                                    // wait before adding active class, for animation
                                    $(this).addClass('active');
                                    next();
                                });
                        }
                    });
                }

                checkForPlayer();
                return;
            }

            // Vimeo video
            if ($container.data('vimeo')) {
                const player = $container.data('VMPlayer');
                if (player) {
                    $button.on('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const $btn = $(this);
                        player.getPaused().then(function (paused) {
                            if (paused) {
                                player.play();
                                $btn
                                .removeClass('active yt-play-button')
                                .html(pauseText)
                                .addClass('yt-pause-button')
                                .attr({
                                    'aria-label': 'Pause background video',
                                    'aria-pressed': true,
                                })
                                .delay(150).queue(function (next) {
                                    // wait before adding active class, for animation
                                    $(this).addClass('active');
                                    next();
                                });
                            } else {
                                player.pause();
                                $btn
                                .removeClass('active yt-pause-button')
                                .html(playText)
                                .addClass('yt-play-button')
                                .attr({
                                    'aria-label': 'Play background video',
                                    'aria-pressed': false,
                                })
                                .delay(150).queue(function (next) {
                                    // wait before adding active class, for animation
                                    $(this).addClass('active');
                                    next();
                                });
                            }
                        });
                    });
                }
            }
        }
    }

});