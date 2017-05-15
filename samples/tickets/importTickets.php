<?php
/**
 * importTickets.php
 * @author: Marco Martins <mapma@fynskemedier.dk>
 * @since:  15-05-2017
 */
include("../../src/Zendesk.php");

use Zendesk\API\Ticket;

/**
 * check https://developer.zendesk.com/rest_api/docs/core/ticket_import#ticket-import
 * for data
 */

$ticketData = array(
	'requester_id' => 0000000000,
	'assignee_id' => 0000000000,
	'status' => 'solved',
	'tags'  => array('demo', 'testing', 'api', 'zendesk', 'import'),
	'subject'  => 'Importing issues.',
	'description'  => 'A long description.',
	'comments'  => array(
		array(
			'value' => 'A comment text.',
			'public' 	=> true,
			'author_id' => 000000000000
		),
		array(
			'value' => 'Another comment text.',
			'public' 	=> false,
			//'author_id' => 000000000
		),
		array(
			'value' => 'This is another comment.',
			'public' 	=> true,
			//'author_id' => 0000000000000,
			'created_at' => '2009-06-25T10:15:18Z'
		),
	)
);

$ticket = new Ticket();
$result = $ticket->import($ticketData);

echo '<pre>';
print_r($result);
echo '</pre>';