<?php
/**
 * createUser.php
 * @author: Marco Martins <mapma@fynskemedier.dk>
 * @since:  20-04-2017
 */
include("../../src/Zendesk.php");

use Zendesk\API\User;

/**
 * check https://developer.zendesk.com/rest_api/docs/core/users#json-format-for-end-user-requests
 * for data
 */

$userData = array(
	'name' => 'User API',
	'email' => 'email@example.com',
	'phone' => '+1-954-704-6031',
	'role'  => 'end-user',
	'details' => 'This user has been created with the API.',
	'external_id' => '12345'
);

$user = new User();
$result = $user->createOrUpdate($userData);

echo '<pre>';
print_r($result);
echo '</pre>';