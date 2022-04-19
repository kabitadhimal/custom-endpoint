
<section class="blog-feature recent-news">
    <div class="container">
        <div class="blog-wrap">
            <div class="blog-post-wrap">

                <?php if( isset( $settings ) & !empty( $settings['filter_block_title'] ) ): ?>
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <h2><?=$settings['filter_block_title']?></h2>
                </div>
                <?php endif; ?>
                <div class="blog-news">
                    <div class="feature-post similar-post d-flex flex-wrap">
                        <?php
                        while ($query->have_posts()):
                            $query->the_post();
                            $contentReadTime = $this->estimated_reading_time(get_the_content());
                            $image = wp_get_attachment_url( get_post_thumbnail_id() );
                            ?>
                            <div class="post-count">
                                <?php
                                if (empty( $image ) ) {
                                    echo ' <div class="image-wrap no-image"></div>';
                                }

                                if( isset( $image ) && !empty( $image ) ) { ?>
                                    <div class="post-image">
                                        <img src="<?=$image?>" alt="image">
                                    </div>
                                <?php } ?>
                                <div class="slide-content post-content">
                                    <div class="slide-subtitle">
                                        <div class="tag-date">
                                            <div class="sub-header">
                                                <div class="header-date">
                                                    <p><?=get_the_date( 'F j, Y' )?></p>
                                                </div>
                                                <div class="read-time">
                                                    <p><?=$contentReadTime?> min read</p>
                                                </div>
                                            </div>
                                        </div>
                                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        <?php the_excerpt(); ?>
                                    </div>
                                    <div class="blog-items-footer">
                                        <a href="<?php the_permalink(); ?>" class="btn btn-primary">Read More </a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile;
                        wp_reset_query(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
