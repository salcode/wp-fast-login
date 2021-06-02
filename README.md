# WP Fast Login

Do NOT use this plugin on a production website. This plugin should only be used on a local development site because it allows logging in as a user without providing the password.

This WordPress development plugin allows logging into directly from the login page (without entering a password or typing a username). The username is chosen from a drop-down menu.

![wp-fast-login](https://user-images.githubusercontent.com/5194588/120109505-b640e380-c137-11eb-846d-8b685e66752e.gif)

## Use as mu-plugin

This plugin can be used as a WordPress `mu-plugin` (for more information about `mu-plugins` see [functions.php vs plugin vs mu-plugin for WordPress](https://salferrarello.com/functions-plugin-mu-plugin-wordpress/)).

Copying the `wp-fast-login.php` into your `wp-content/mu-plugin` directory will add this as an active plugin to your WordPress site.

A quick way to add this file is to navigate to your `mu-plugins` directory and use this command to download the latest version of the `wp-fast-login.php` file.

```
curl -O https://raw.githubusercontent.com/salcode/wp-fast-login/main/wp-fast-login.php
```

## Credits

[Sal Ferrarello](https://salferrarello.com) / [@salcode](https://twitter.com/salcode)

### Inspired By

Inspired by ServerPress's [Bypass Login](https://serverpress.com/featuring-bypass-login/) plugin.
