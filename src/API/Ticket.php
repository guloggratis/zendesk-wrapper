<?php
namespace Zendesk\API;
/**
 * Ticket.php
 * @author: Marco Martins <mapma@fynskemedier.dk>
 * @since:  05-04-2017
 */
use Zendesk\Resources\Core\Tickets;
use Zendesk\Resources\Core\Attachments;

/**
 * Class Ticket
 * @package Zendesk\API
 */
class Ticket extends Wrapper {

	/**
	 * Creates a ticket
	 *
	 * @param array $ticketData
	 * @return array
	 */
	public function create(array $ticketData = array()) {
		if (!isset($ticketData['requester']) || !$this->validateUserFields($ticketData['requester'])) {
			echo 'In order to create a ticket you need to have (correct) requester data!' . PHP_EOL;
			echo 'Check https://developer.zendesk.com/rest_api/docs/core/tickets#creating-a-ticket-with-a-new-requester for more information' . PHP_EOL;
			exit;
		}

		if (!$this->validateTicketFields($ticketData)) {
			echo 'In order to create a ticket you need to have (correct) ticket data!' . PHP_EOL;
			echo 'Check samples/tickets/createTicket.php for more information' . PHP_EOL;
			exit;
		}

		// check for attachment
		$attachmentToken = null;
		if (!empty($ticketData['attachment'])) {
			try {
				$attachmentData = array(
					'file' => $ticketData['attachment'],
					'type' => mime_content_type($ticketData['attachment']),
					'name' => basename($ticketData['attachment']),
				);

				$attachment = new Attachments($this->client);
				$attachment->upload($attachmentData);

				$response = $attachment->getResponse();
				if (isset($response['upload'])) {
					$attachmentToken = $response['upload']['token'];
				} elseif (isset($response['error'])) {
					$result['error'] = array(
						'title' 	=> $response['error']['title'],
						'message' 	=> $response['error']['message'],
						'hint' 		=> 'Check your config/zendesk.php file.',
					);
				}

			} catch (\Exception $e) {
				echo $e->getMessage() . PHP_EOL;
				exit;
			}
		}

		// set the attachment token to the ticket request
		if (!empty($ticketData['attachment']) && $attachmentToken != null) {
			$ticketData['comment']['uploads'] = $attachmentToken;
		}

		try {
			$result = array('success' => false);

			$newTicket = new Tickets($this->client);
			$newTicket->create($ticketData);

			$response = $newTicket->getResponse();
			if (isset($response['ticket'])) {
				$result = array(
					'success' 	=> true,
					'ticket_id' => $response['ticket']['id']
				);
			} elseif (isset($response['error'])) {
				$result['error'] = array(
					'title' 	=> $response['error']['title'],
					'message' 	=> $response['error']['message'],
					'hint' 		=> 'Check your config/zendesk.php file.',
				);
			}

			return $result;
		} catch (\Exception $e) {
			echo $e->getMessage() . PHP_EOL;
			exit;
		}
	}

}