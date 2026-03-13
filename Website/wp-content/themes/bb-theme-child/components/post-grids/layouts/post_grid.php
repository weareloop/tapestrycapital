<?php
$layout = 'grid';
$readmore = (isset($_GET['readmore'])) ? $_GET['readmore'] : 'readmore';
$numberperpage = (isset($_GET['numberperpage'])) ? intval($_GET['numberperpage']) : 6;
        echo "<div class='posts ".$layout."'>";
            foreach($posts as $key=>$post){
                    
                    // Only taxonomy "TYPE"
                    
                    
                    $taxo_type = get_the_terms($post->ID, 'story_format');

                    $external_link  = get_field("external_link", $post->ID);
                    $btn_text  = get_field("btn_text", $post->ID) ? : "Read More";
                    $desc = get_field("short_description", $post->ID);
                    $link_type = trim(get_field("link_url", $post->ID));

                    if($external_link){
                        $link_url = $external_link;
                        $link_target = '_blank';
                    }else{
                        $link_url = get_permalink($post->ID);
                        $link_target = '_self';
                    }

                    
                ?>
                <div class="post <?php echo "page_".(intval($key / $numberperpage) + 1); echo (intval($key / $numberperpage) ==0) ? " active visual" : "" ?> <?php if($external){echo " external_post"; }?>">
                    <div class="postBox">
                        <div class="postTop">
                            <div class="imgBox">
                                <?php if(!empty($taxo_type)){ ?>
                                    <div class="tags">
                                        <?php 
                                            foreach ($taxo_type as $kay => $cd) {
                                                if ($cd->term_id == 1) {
                                                    continue;
                                                } else {
                                                    if ($kay != 0) {
                                                        echo ', ';
                                                    }
                                                    echo $cd->name;
                                                }
                                            }
                                        ?>
                                    </div>
                                <?php } ?>
                                <?php   echo (get_the_post_thumbnail( $post->ID, 'thumbnail')=="")?'<div class="defaultImg"></div>':get_the_post_thumbnail( $post->ID, 'thumbnail');    ?>
                            </div>
                        </div>
                        <div class="postBottom">
                                <h3 class="h4">
                                    <a target="<?php echo $link_target; ?>" href="<?php echo $link_url; ?>" arial-label="<?php echo $post->post_title; ?>">
                                        <?php echo $post->post_title; ?>
                                    </a>
                                </h3>
                                <?php if ($desc) { ?><div class="desc"><?php echo $desc; ?></div><? } ?>
                                <div class="date"><?php echo date('d M Y', strtotime($post->post_date)); ?></div>
                        </div>
                    </div>
                </div>
                <?php 
            }
                echo "</div>";
            ?>
            <?php if($readmore == "readmore"){ ?>
                <div class="loadMore">
                    <button class="loadMore_btn <?php echo $layout; ?> perpage_<?php echo $numberperpage; ?>">
                        <?php echo 'Load More'; ?>
                    </button>
                </div>
            <?php }elseif($readmore == "pagination"){ ?>
                <div class="loadMore">
                    <?php 
                    for($i = 0; $i < intval(count($posts) / $numberperpage)+1; $i++){
                    ?>
                        <button class="page_btn <?php if($i==0){ echo ' active'; } ?> "><?php echo $i+1; ?></button>
                    <?php
                    }
                    ?>
                </div>
            <?php } ?>