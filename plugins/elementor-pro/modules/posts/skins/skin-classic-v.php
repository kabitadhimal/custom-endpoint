<?php
namespace ElementorPro\Modules\Posts\Skins;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Skin_Classic_V extends Skin_Classic {


	public function get_id() {
		return 'classic_v';
	}

	public function get_title() {
		return esc_html__( 'Classic_V', 'elementor-pro' );
	}

	
}
