document.addEventListener('DOMContentLoaded', function(event) {
  document.getElementById('fast-login').addEventListener(
    'input', function(evt) {
      console.log(this.value);
    }
  );
});

