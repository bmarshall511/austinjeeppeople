<?php
/**
 * Login functionality
 *
 * @package HelloElementor
 * @subpackage HelloElementorChild
 * @since 1.0.0
 */

function hello_elementor_child_login() {
  $logo = get_theme_mod( 'custom_logo' );
  $image = wp_get_attachment_image_src( $logo , 'full' );
  ?>
  <style type="text/css">
  #login h1 a, .login h1 a {
    background-image: url('<?php echo $image[0]; ?>');
    background-size: contain;
    height: 25px;
    width: 320px;
    background-repeat: no-repeat;
  }
  </style>
<?php }
add_action( 'login_enqueue_scripts', 'hello_elementor_child_login' );

function hello_elementor_child_logo_url() {
  return home_url();
}
add_filter( 'login_headerurl', 'hello_elementor_child_logo_url' );

function hello_elementor_child_logo_url_title() {
  return get_bloginfo( 'name' );
}
add_filter( 'login_headertitle', 'hello_elementor_child_logo_url_title' );

function hello_elementor_child_login_scripts() {
  wp_enqueue_style( 'hello-elementor-child-login', get_stylesheet_directory_uri() . '/assets/css/add-ons/wordpress/login.css' );
  wp_enqueue_style( 'hello-elementor-child-login-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap' );
}
add_action( 'login_enqueue_scripts', 'hello_elementor_child_login_scripts' );
