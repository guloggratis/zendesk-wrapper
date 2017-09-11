<?php
/**
 * createTicket.php
 * @author: Marco Martins <mapma@fynskemedier.dk>
 * @since:  06-04-2017
 */
include("../../src/Zendesk.php");

use Zendesk\API\Ticket;

/**
 * check https://developer.zendesk.com/rest_api/docs/core/tickets#request-parameters
 * for data
 */

$ticketData = array(
	'status' => 'new',
	'type' => 'problem',
	'priority' => 'normal',
	'tags'  => array('demo', 'testing', 'api', 'zendesk'),
	'subject'  => 'This is a new issue.',
	'comment'  => array(
		'body' => 'I am creating a ticket in order to test the zendesk api.'
	),
	'requester' => array(
		'name' => 'Api Test User',
		'email' => 'email@example.com',
	),
	// comment out next lines to create ticket with attachment
	/*
	'attachment' => array(
		'path' => getcwd() . '/sample-logo.jpg',
		'type' => 'image/jpg;',
		'name' => 'sample-logo'
	),
	*/
);

$ticket = new Ticket();
$result = $ticket->create($ticketData);

echo '<pre>';
print_r($result);
echo '</pre>';