<?php
/**
 * countTickets.php
 * @author: Marco Martins <mapma@fynskemedier.dk>
 * @since:  20-04-2017
 */
include("../../src/Zendesk.php");

use Zendesk\API\User;

$userId 		= '123456';
$userEmail 		= 'email@example.com';

$user = new User();
$result = $user->countTickets($userId);

echo '<pre>';
print_r($result);
echo '</pre>';

