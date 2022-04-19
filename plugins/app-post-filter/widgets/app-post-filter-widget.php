<?php
class App_Post_Filter_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'App-post-filter';
    }

    public function get_title() {
        return __( 'App Post Filter', 'app-elementor-post-filter' );
    }

    public function get_icon() {
        return 'eicon-filter';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'app-elementor-post-filter' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'filter_block_title',
            [
                'label' => __( 'Filter Block Title', 'app-elementor-post-filter' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => "",
                'title' => __( 'Enter text for recent post title', 'app-elementor-post-filter' ),
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
        $pagePermalink = get_permalink();
        include 'post/post-query.php';
   /*     $numberOfPosts =  isset( $settings['number_of_posts'] ) ? (int) $settings['number_of_posts'] : 3;
            $query = new WP_Query(['post_type' => 'post',
                'post_status' => 'publish', 'posts_per_page' => $numberOfPosts]);*/
            if ($query->have_posts()):
                include 'post/post-list.php';
            ?>
        <?php
            else:
                echo "No Post Found";
            endif;
            ?>
<?php
    }
}
