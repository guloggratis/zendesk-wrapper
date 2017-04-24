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
	'type' => 'problem',
	'priority' => 'normal',
	'tags'  => array('demo', 'testing', 'api', 'zendesk'),
	'subject'  => 'Hello, I think I have a problem.',
	'comment'  => array(
		'body' => 'I am creating a ticket in order to test the zendesk api.'
	),
	'requester' => array(
		'name' => 'Api Test User',
		'email' => 'email@example.com";'
	),
	// comment out next line to create ticket with attachment
	//'attachment' => getcwd() . '/sample-logo.jpg',
);

$ticket = new Ticket();
$result = $ticket->create($ticketData);

echo '<pre>';
print_r($result);
echo '</pre>';