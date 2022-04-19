<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Total WordPress Theme
 * @subpackage Templates
 * @version 4.5.4
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?><!DOCTYPE html>
<html <?php language_attributes(); ?><?php wpex_schema_markup( 'html' ); ?>>
<head>

<meta charset="<?php bloginfo( 'charset' ); ?>">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
	
<body <?php body_class(); ?>>
<div style="color:white;"><?php echo $texas; ?>	</div>
	<?php wpex_outer_wrap_before(); ?>

	<div id="outer-wrap" class="clr">

		<?php wpex_hook_wrap_before(); ?>

		<div id="wrap" class="clr">

			<?php wpex_hook_wrap_top(); ?>

			<?php wpex_hook_main_before(); ?>
	
	<!-- Breadcrumbs .headerphp (Location Pages) -->
<header class="home-hidden page-header wpex-supports-mods"><div class="page-header-inner container clr"><h3 class="page-header-title wpex-clr" itemprop="headline"><span><?php the_title(); ?></span></h3><nav class="site-breadcrumbs wpex-clr  position-absolute has-js-fix<?php breadcrumb_simple(); ?> </nav></div>
	</header>
	<!-- Breadcrumbs .headerphp (Location Pages) End -->
			
			<main id="main" class="site-main clr"<?php wpex_schema_markup( 'main' ); ?><?php wpex_aria_landmark( 'main' ); ?>>

				<?php wpex_hook_main_top(); ?>