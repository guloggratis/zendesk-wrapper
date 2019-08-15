# Zendesk Wrapper for GulogGratis.dk

Zendesk API wrapper for PHP 5.3+

## Requirements
* PHP 5.3+
* Zendesk API v2 [documentation](http://developer.zendesk.com)

## Inspiration
* https://github.com/zendesk/zendesk_api_client_php
* https://github.com/huddledigital/zendesk-laravel
* Modified to fit our php version (PHP 5.3+)

## Installation
* Checkout into your project.
* Copy config/zendesk.template.php to config/zendesk.php
* Set your configuration variables
```
// config/zendesk.php
$configuration = array(
	'subdomain' => 'my-sub-domain',
	'username'  => 'my-username',
	'token'     => 'my-token-12345',
	'apiUrl'    => 'my-api-url-if-any',
);
```

## Implementation
See [samples/](samples/) folder for implementation

