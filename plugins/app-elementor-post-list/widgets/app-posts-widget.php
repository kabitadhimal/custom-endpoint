<?php
class App_Recent_Posts_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'App-recent-posts';
    }

    public function get_title() {
        return __( 'App Recent Posts', 'app-elementor-recent-post' );
    }

    public function get_icon() {
        return 'eicon-info-box';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'app-elementor-recent-post' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'recent_post_title',
            [
                'label' => __( 'Recent Post Title', 'app-elementor-recent-post' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => "",
                'title' => __( 'Enter text for recent post title', 'app-elementor-recent-post' ),
            ]
        );

        $this->add_control(
            'post_listing_page_link',
            [
                'label' => __( 'Link to Posts listing page ', 'app-elementor-recent-post' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'title' => __( 'Enter Link to Posts listing page', 'app-elementor-recent-post' ),
            ]
        );

        $this->add_control(
            'number_of_posts',
            [
                'label' => __( 'Number of Posts to display', 'app-elementor-recent-post' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 3,
                'title' => __( 'Enter number of posts to display', 'app-elementor-recent-post' ),
            ]
        );


        $this->end_controls_section();

    }


     /*
     * Reading time of the article
     */
    protected function estimated_reading_time( $content = '', $wpm = 200 ) {
        $clean_content = strip_shortcodes( $content );
        $clean_content = strip_tags( $clean_content );
        $word_count = str_word_count( $clean_content );
        $time = ceil( $word_count / $wpm );

        return $time;
    }

    protected function render() {
        // generate the final HTML on the frontend using PHP
        $settings = $this->get_settings_for_display();
        $numberOfPosts =  isset( $settings['number_of_posts'] ) ? (int) $settings['number_of_posts'] : 3;
            $query = new WP_Query(['post_type' => 'post',
                'post_status' => 'publish', 'posts_per_page' => $numberOfPosts]);
            if ($query->have_posts()):
            ?>
                <section class="blog-feature recent-news">
                    <div class="container">
                        <div class="blog-wrap">
                            <div class="blog-post-wrap">
                                <div class="d-flex flex-wrap align-items-center justify-content-between">
                                    <?php if( isset($settings['recent_post_title'])) {
                                        echo "<h2>".$settings['recent_post_title']."</h2>";
                                    }

                                    if( isset( $settings['post_listing_page_link'] ) && !empty( isset( $settings['post_listing_page_link'] ) ) ) {
                                        echo '<a href="'.$settings['post_listing_page_link'].'">View All</a>';
                                    }
                                    ?>
                                </div>
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
        <?php
            endif;
    }
}
