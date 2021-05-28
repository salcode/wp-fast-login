<?php

namespace salcode\fastLogin;

add_action( 'login_form', __NAMESPACE__ . '\login_form' );

function login_form() {
	$args = [
		'number' => 100,
		'role' => 'Administrator',
	];
	$user_query = new \WP_User_Query( $args );
?>
<div>
	<label for="fast-login">Choose account to fast login</label><br>
	<select
		id="fast-login"
	>
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
	<script>
		document.getElementById('fast-login').addEventListener(
			'input', function(evt) {
				console.log(this.value);
			}
		);
	</script>
</div>
<?php
}

function printUserOptionTags( $args ) {
	$user_query = new \WP_User_Query( $args );
	foreach ( $user_query->get_results() as $user ) {
		printf(
			'<option value="%d">%s</option>',
			$user->id,
			$user->display_name
		);
	}
}
