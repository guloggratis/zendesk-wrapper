<?php
/**
 * Returns user data based on the email or user id
 *
 * getUser.php
 * @author: Marco Martins <mapma@fynskemedier.dk>
 * @since:  20-04-2017
 */

include("../../src/Zendesk.php");

use Zendesk\API\User;

$userId 		= "123456";
$userEmail 		= "email@example.com";

$user = new User();
$result = $user->getById($userId);
//$result = $user->getByEmail($userEmail);

echo '<pre>';
print_r($result);
echo '</pre>';
