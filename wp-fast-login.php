<?php
/**
 * Plugin Name:       WP Fast Login
 * Plugin URI:        https://github.com/salcode/wp-fast-login/
 * Description:       Do NOT use this plugin on a production site. This plugin adds a drop-down menu on the login page for a fast login (without a password).
 * Version:           0.1.0
 * Requires at least: 5.2
 * Requires PHP:      5.6
 * Author:            Sal Ferrarello
 * Author URI:        https://salferrarello.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-fast-login
 * Domain Path:       /languages
 */

namespace salcode\FastLogin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'login_form', __NAMESPACE__ . '\print_user_dropdown' );
add_action( 'login_enqueue_scripts', __NAMESPACE__ . '\enqueue_scripts' );
add_action( 'rest_api_init', __NAMESPACE__ . '\add_rest_api_route');

function add_rest_api_route() {
	register_rest_route( 'wp-fast-login/v1', '/login/(?P<id>[\d]+)', [
		'callback' => function( $request ) {
			$user_id = $request['id'];
			wp_set_auth_cookie( $user_id, TRUE );
			return rest_ensure_response([ 'userId' => $user_id ]);
		},
		'permission_callback' => '__return_true',
		'methods'  => \WP_REST_Server::CREATABLE, // POST
	]);
}

function enqueue_scripts() {
	wp_enqueue_script( 'wp-util' );
	wp_localize_script(
		'wp-util',
		'wpFastLogin',
		[
			'destination' =>
				filter_input( INPUT_GET, 'redirect_to', FILTER_VALIDATE_URL ) ?:
				get_admin_url(),
			'restUrl' => get_rest_url(),
		]
	);
}

function print_user_dropdown() {
	$args = [
		'number' => 100,
		'role' => 'Administrator',
	];
	$user_query = new \WP_User_Query( $args );
?>
<div>
	<label for="fast-login">Fast login</label><br>
	<select
		id="fast-login"
	>
		<option default value="">Select a User</option>
		<?php
			print_user_option_tags( [
				'number' => 100,
				'role' => 'Administrator'
			]);

			print_user_option_tags( [
				'number' => 100,
				'role__not_in' => [ 'Administrator' ]
			]);
		?>
	</select>
	<br><br>
</div>
<script>
document.getElementById('fast-login').addEventListener(
  'input', async function(evt) {
    if (! this.value) {
      // No user selected. Return early with no changes.
       return;
    }
    try {
      const userId = await fetch(
        `${wpFastLogin.restUrl}wp-fast-login/v1/login/${this.value}`,
        { method: 'POST' }
      ).then((response) => response.json())
      .then((json) => json.userId);

      if (! userId) {
        throw new Error( 'Attempt to login failed' );
      }
      window.location.href = wpFastLogin.destination;
    } catch (err) {
      alert(err);
    }
  }
);
</script>
<?php
}

function print_user_option_tags( $args ) {
	$user_query = new \WP_User_Query( $args );
	foreach ( $user_query->get_results() as $user ) {
		printf(
			'<option value="%d">%s (%s)</option>',
			$user->ID,
			esc_html( $user->user_login ),
			esc_html( implode( ',', $user->roles ) )
		);
	}
}
