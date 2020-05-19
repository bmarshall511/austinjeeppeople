<?php
/**
 * Theme shortcodes
 *
 * @package HelloElementor
 * @subpackage HelloElementorChild
 * @since 1.0.0
 */

// [the_content]
function hello_elementor_child_shortcode_the_content( $atts ) {
	the_content();
}
add_shortcode( 'the_content', 'hello_elementor_child_shortcode_the_content' );

// [complete_address]
function hello_elementor_child_shortcode_complete_address() {
  $address = get_field( 'ajp_address' );

  if ( ! $address ) { return; }

  $full_address = false;

  if ( ! empty( $address['address_1'] ) ) {
    $full_address = $address['address_1'];
  }

  if ( ! empty( $address['address_2'] ) ) {
    if ( $full_address ) { $full_address .= ' '; }
    $full_address .= $address['address_2'];
  }

  if ( ! empty( $address['city'] ) ) {
    if ( $full_address ) { $full_address .= ', '; }
    $full_address .= $address['city'];
  }

  if ( ! empty( $address['state'] ) ) {
    if ( $full_address ) { $full_address .= ', '; }
    $full_address .= $address['state'];
  }

  if ( ! empty( $address['zip'] ) ) {
    if ( $full_address ) { $full_address .= ' '; }
    $full_address .= $address['zip'];
  }

  return $full_address;
}
add_shortcode( 'complete_address', 'hello_elementor_child_shortcode_complete_address' );

// [profile_url]
function hello_elementor_child_shortcode_profile_url() {
  return get_author_posts_url( get_current_user_id() );
}
add_shortcode( 'profile_url', 'hello_elementor_child_shortcode_profile_url' );

// [account_url]
function hello_elementor_child_shortcode_account_url() {
  return get_author_posts_url( get_current_user_id() );
}
add_shortcode( 'account_url', 'hello_elementor_child_shortcode_account_url' );

// [members]
function hello_elementor_child_shortcode_members() {
  $args = [];

  $args['orderby'] = 'user_registered';

  $query   = new WP_User_Query( $args );
  $members = $query->get_results();

  if ( ! $members ) { return; }

  ob_start();
  ?>
  <div class="hec-members">
    <?php foreach( $members as $key => $member ):
      $basic   = get_field( 'user_basic', 'user_' . $member->ID );
      $contact = get_field( 'user_contact', 'user_' . $member->ID );
      $profile = get_field( 'user_profile', 'user_' . $member->ID );
      ?>
      <a href="<?php echo esc_url( get_author_posts_url( $member->ID ) ); ?>" class="hec-member">
        <div class="hec-member-image"><?php echo get_avatar( $member->ID, 200, '', $member->display_name ); ?></div>
        <div class="hec-member-info">
          <h3><?php echo $member->display_name; ?></h3>
          <ul>
            <?php if ( ! empty( $basic['gender'] ) ): ?><li><?php echo $basic['gender']; ?></li><?php endif; ?>
            <?php if ( ! empty( $basic['dob'] ) ): ?><li><?php echo hello_elementor_child_helper_calculate_age( $basic['dob'] ); ?></li><?php endif; ?>
            <?php if ( ! empty( $contact['city'] ) || ! empty( $contact['state'] ) ): ?>
              <li>
                <?php if ( ! empty( $contact['city'] )): ?><?php echo $contact['city']; ?><?php endif; ?>
                <?php if ( ! empty( $contact['city'] ) && ! empty( $contact['state'] ) ): ?>, <?php endif; ?>
                <?php if ( ! empty( $contact['state'] )): ?><?php echo $contact['state']; ?><?php endif; ?>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
  <?php
	return ob_get_clean();
}
add_shortcode( 'members', 'hello_elementor_child_shortcode_members' );
