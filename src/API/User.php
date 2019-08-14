<?php
namespace Zendesk\API;
/**
 * User.php
 * @author: Marco Martins <mapma@fynskemedier.dk>
 * @since:  05-04-2017
 */
use Zendesk\Resources\Core\Users;
use Zendesk\Resources\Core\UserTickets;

/**
 * Class User
 * @package Zendesk\API
 */
class User extends Wrapper {

	/**
	 * @var array
	 */
	var $user = array();

	/**
	 * Creates or updates a user
	 *
	 * @param array $userData
	 * @return array
	 */
	public function createOrUpdate(array $userData = array()) {
		if (!$this->validateUserFields($userData)) {
			echo 'In order to create a user you need to have (correct) user data!' . PHP_EOL;
			echo 'Check https://developer.zendesk.com/rest_api/docs/core/users#create-or-update-user for more information' . PHP_EOL;
			return false;
		}

		try {
			$user = new Users($this->client);
			$user->createOrUpdate($userData);

			$result['meta']['status'] = $user->getStatusCode();
			$response = $user->getResponse();
			if (isset($response['user'])) {
				$this->user = $response['user'];

				$result['data'] = array(
					'user_id' => $response['user']['id'],
					'user_external_id' => $response['user']['external_id'],
				);
			} elseif (isset($response['error'])) {
				$result['error'] = array(
					'title' 	=> $response['error']['title'],
					'message' 	=> $response['error']['message'],
					'hint' 		=> 'Check your config/zendesk.php file.',
					'err' 		=> $response,
				);
			}

			return $result;
		} catch (\Exception $e) {
			echo $e->getMessage() . PHP_EOL;
			return false;
		}
	}

	/**
	 * Returns user by user zendesk id
	 *
	 * @param string $userId
	 * @return array
	 */
	public function getById($userId) {
		try {
			$user = new Users($this->client);
			$user->getUser(array('id' => $userId));

			$result['meta']['status'] = $user->getStatusCode();
			$response = $user->getResponse();
			if (isset($response['user'])) {
				$this->user = $response['user'];
				$result['data'] = $response['user'];

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
			return false;
		}
	}

	/**
	 * Gets the tickets submitted by the user
	 *
	 * @param string $userId The user id in zendesk
	 * @return array
	 */
	public function getTickets($userId = '') {
		if ($userId === '') {
			if (empty($this->user)) {
				echo __METHOD__ . ' you need a valid user, or a userId as param' . PHP_EOL;
				return false;
			}
		} else {
			$this->user['id'] = $userId;
		}

		try {
			$userTickets = new UserTickets($this->client);
			$userTickets->requested(array('id' => $this->user['id']));

			$result['meta']['status'] = $userTickets->getStatusCode();
			$response = $userTickets->getResponse();
			if (isset($response['tickets'])) {
				$result['data'] = array(
					'tickets' 		=> $response['tickets']
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
			return false;
		}
	}

	/**
	 * Returns number of tickets submitted by the user
	 * 		- total
	 * 		- new
	 * 		- open
	 * 		- solved
	 *
	 * @param string $userId The user id in zendesk
	 * @return array
	 */
	public function countTickets($userId = '') {
		if ($userId === '') {
			if (empty($this->user)) {
				echo __METHOD__ . ' you need a valid user, or a userId as param' . PHP_EOL;
				return false;
			}
		} else {
			$this->user['id'] = $userId;
		}

		try {
			$userTickets = new UserTickets($this->client);
			$userTickets->requested(array('id' => $this->user['id']));

			$result['meta']['status'] = $userTickets->getStatusCode();
			$response = $userTickets->getResponse();
			if (isset($response['tickets'])) {
				$totalTickets = 0;
				$totalNewTickets = 0;
				$totalOpenTickets = 0;
				$totalSolvedTickets = 0;

				foreach ($response['tickets'] as $tickets) {
					if ($tickets['status'] == 'new') {
						$totalNewTickets += 1;
					}
					if ($tickets['status'] == 'open') {
						$totalOpenTickets += 1;
					}
					if ($tickets['status'] == 'solved') {
						$totalSolvedTickets += 1;
					}
					$totalTickets += 1;
				}

				$result['data'] = array(
					'total_tickets' 		=> $totalTickets,
					'total_new_tickets' 	=> $totalNewTickets,
					'total_open_tickets' 	=> $totalOpenTickets,
					'total_solved_tickets' 	=> $totalSolvedTickets,
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
			return false;
		}
	}

	/**
	 * NOTE: only use this method if you don't have a Zendesk user id
	 *		 in that case use countTickets()
	 *
	 * Returns user by email
	 *
	 * @param string $email
	 * @return array
	 */
	public function getByEmail($email) {
		try {
			$user = new Users($this->client);
			$user->search(array('query' => 'email:'.$email));

			$result['meta']['status'] = $user->getStatusCode();
			$response = $user->getResponse();
			if (isset($response['users'])) {
				$this->user = $response['users'][0];

				$result['data'] = $response['users'][0];
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
			return false;
		}
	}

	/**
	 * Deletes a user
	 *
	 * @param string $userId The user id in zendesk
	 * @param booelan $permanently <false> permanently delete the user
	 * @return array
	 */
	public function delete($userId = '', $permanently = false) {
		try {

			$ticketClient = new Ticket($this->client);
			$userClient = new Users($this->client);

			// get a user's tickets
			$result = $this->getTickets($userId);
			if (($result["meta"]["status"] == 200) and isset($result["data"]["tickets"])) {
				// make a list of tickets that need to be forcefully closed
				$ids = array();
				foreach ($result["data"]["tickets"] as $ticket) {
					array_push($ids, $ticket["id"]);
				}
			}

			if (count($ids)) {
				// bulk close the tickets
				$ticketData = array(
					"status" => "closed",
					"ids" => join(",", $ids)
				);
				$result = $ticketClient->bulkUpdate($ticketData);
			}

			$attempts = 1;

			// there is a race condition between the bulkUpdate of tickets and hte user deletion
			// we make multiple attempts, waiting a bit more in between each of them, up to a max number of tries

			while($attempts <= 5) {
				// delete the user
				if (!$permanently) {
					$userClient->deleteUser(array('id' => $userId));
				} else {
					$userClient->permanentlyDeleteUser(array('id' => $userId));
				}
				$result['meta']['status'] = $userClient->getStatusCode();
				$response = $userClient->getResponse();
				if ($userClient->getStatusCode() == 200) {
					break;
				} else {
					sleep (2 * $attempts);
				}
				$attempts++;
			}

			if (isset($response['error'])) {
				$result['error'] = array(
					'title' 	=> $response['error'],
					'message' 	=> $response['description'],
					'err' 		=> $response,
				);
			} else {
				$result['data'] = $response;
			}

			return $result;
		} catch (\Exception $e) {
			echo $e->getMessage() . PHP_EOL;
			return false;
		}
	}
}