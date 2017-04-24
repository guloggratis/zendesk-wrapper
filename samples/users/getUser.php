<?php
/**
 * Returns user data based on the email or external id
 *
 * getUser.php
 * @author: Marco Martins <mapma@fynskemedier.dk>
 * @since:  20-04-2017
 */

include("../../src/Zendesk.php");

use Zendesk\API\User;

$userId 		= "123456";
$userEmail 		= "email@example.com";
$userExternalId = "12345";

$user = new User();

$result = $user->getById($userId);
//$result = $user->getByEmail($email);
//$result = $user->getByExternalId($externalId);

echo '<pre>';
print_r($result);
echo '</pre>';
