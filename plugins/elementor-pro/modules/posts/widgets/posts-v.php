<?php
namespace ElementorPro\Modules\Posts\Widgets;

use Elementor\Controls_Manager;
use ElementorPro\Modules\QueryControl\Module as Module_Query;
use ElementorPro\Modules\QueryControl\Controls\Group_Control_Related;
use ElementorPro\Modules\Posts\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Posts
 */
class Posts_V extends Posts {


	public function get_name() {
		
		return 'posts_v';
	}

	public function get_title() {
		return esc_html__( 'Posts_V', 'elementor-pro' );
	}

	protected function register_skins() {
		//$this->add_skin( new Skins\Skin_Classic( $this ) );
		//$this->add_skin( new Skins\Skin_Cards( $this ) );
		//$this->add_skin( new Skins\Skin_Full_Content( $this ) );
		parent::register_skins();
		$this->add_skin( new Skins\Skin_Classic_V( $this ) );
	}


}
