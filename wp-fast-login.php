<?php

namespace salcode\fastLogin;

add_action( 'login_form', __NAMESPACE__ . '\login_form' );
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
	wp_localize_script(
		'wp-util',
		'wpFastLogin',
		[
			'destination' => get_admin_url(),
			'restUrl' => get_rest_url(),
		]
	);
}

function login_form() {
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
			printUserOptionTags( [
				'number' => 100,
				'role' => 'Administrator'
			]);

			printUserOptionTags( [
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

function printUserOptionTags( $args ) {
	$user_query = new \WP_User_Query( $args );
	foreach ( $user_query->get_results() as $user ) {
		printf(
			'<option value="%d">%s</option>',
			$user->ID,
			$user->display_name
		);
	}
}
