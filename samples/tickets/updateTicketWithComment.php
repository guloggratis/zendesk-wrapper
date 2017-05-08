<?php
/**
 * Adds a comment/message to a existing ticket
 * 	It can be internal note (public => false)
 *	Depending og the author_id it will add the user as the owner of the comment
 *  It requires a ticket id
 * 	Extra data on the ticketData will update the main ticket status
 * 		ex: 'status','type','priority', etc
 *
 * updateTicketWithComment.php
 * @author: Marco Martins <mapma@fynskemedier.dk>
 * @since:  05-05-2017
 */
include("../../src/Zendesk.php");

use Zendesk\API\Ticket;

$ticketData = array(
	//'id' => 1,
	'comment'  => array(
		//'public' 	=> false,
		'body' 		=> 'This is just a comment.',
		//'author_id' => 11111111
	),
	//'attachment' => getcwd() . '/sample-logo.jpg',
);

$ticket = new Ticket();
$result = $ticket->update($ticketData);

echo '<pre>';
print_r($result);
echo '</pre>';