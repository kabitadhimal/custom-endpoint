<?php
namespace DF\Modules\Posts\Skins;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;
use ElementorPro\Modules\Posts\Skins\Skin_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Skin_Classic extends \ElementorPro\Modules\Posts\Skins\Skin_Classic {

	protected $dfPostSubtitle = 'df_post_subtitle';
	protected $dfTaxonomy = 'industry';

	protected function _register_controls_actions() {
		parent::_register_controls_actions();

		add_action( 'elementor/element/posts/classic_section_design_layout/after_section_end', [ $this, 'register_additional_design_controls' ] );
	}


	protected function register_new_post_count_control() {
		$this->add_control(
			'new_page',
			[
				'label' => esc_html__( 'New Page', 'elementor-pro' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 6,
			]
		);
	}






	public function get_id() {
		return 'classic_v2';
	}

	public function get_title() {
		return esc_html__( 'Classic_v2', 'elementor-pro' );
	}



	protected function register_meta_data_controls() {
		parent::register_meta_data_controls();
		$this->update_control(
			'meta_data',
			[
				'label' => esc_html__( 'Meta Data', 'elementor-pro' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT2,
				'default' => [ 'date', 'comments' ],
				'multiple' => true,
				'options' => [
					'author' => esc_html__( 'Author', 'elementor-pro' ),
					'date' => esc_html__( 'Date', 'elementor-pro' ),
					'time' => esc_html__( 'Time', 'elementor-pro' ),
					'comments' => esc_html__( 'Comments', 'elementor-pro' ),
					'modified' => esc_html__( 'Date Modified', 'elementor-pro' ),
					//custom post meta
					$this->dfPostSubtitle => esc_html__( 'Subtitle', 'elementor-pro' ),
					$this->dfTaxonomy => esc_html__( 'Industry Taxonomy', 'elementor-pro' ), 
				],
				'separator' => 'before',
			],
		);


		$args = array(
			'posts_per_page ' => -1,
			'post_type'   => 'case-study'
		  );
		   
		  $caseStudies = get_posts( $args );

		   $caseStudyArray = [];

		  foreach($caseStudies as $caseStudy){
			setup_postdata( $caseStudy ); 
			$caseStudyArray[$caseStudy->ID] = get_the_title($caseStudy->ID);
		  }
		
		  
		$this->add_control(
			'featured_post',
			[
				'label' => esc_html__( 'Featured Post', 'elementor-pro' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT2,
				//'default' => [ 'date', 'comments' ],
				'multiple' => true,
				'options' =>$caseStudyArray,
				'separator' => 'before',
			]
		);


	}

	protected function renderCustomMeta()
	{
		$settings = $this->get_instance_value( 'meta_data' );
		//var_dump($settings);
		if ( !$settings || !in_array($this->dfPostSubtitle, $settings)) return;

		$metaValue = get_post_meta(get_the_ID(),$this->dfPostSubtitle, true);
	
		if(!$metaValue) return;

		echo <<<EOF
		<div class="elementor-post__subtitle">{$metaValue}</div>
EOF;
	}

	protected function renderIndustryTerms()
	{
		$settings = $this->get_instance_value( 'meta_data' );
		//var_dump($settings);
		if ( !$settings || !in_array($this->dfTaxonomy, $settings)) return;
		$term_obj_list = get_the_terms( get_the_ID(), $this->dfTaxonomy );
		 if(!$term_obj_list) return;
        $terms_string = join(', ', wp_list_pluck($term_obj_list, 'name'));
		if(!$terms_string) return;
		$terms_string = "Industry: ".$terms_string;


		echo <<<EOF
<div class="industry-terms">{$terms_string}</div>
EOF;
	}
	


	protected function render_title() {
		$this->renderCustomMeta();
		parent::render_title();
		$this->renderIndustryTerms();
	}
}
