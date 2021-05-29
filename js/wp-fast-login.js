document.addEventListener('DOMContentLoaded', function(event) {
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
});

