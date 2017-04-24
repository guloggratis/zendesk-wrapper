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
			exit;
		}

		try {
			$result = array('success' => 0);

			$user = new Users($this->client);
			$user->createOrUpdate($userData);

			$response = $user->getResponse();
			if (isset($response['user'])) {
				$this->user = $response['user'];

				$result = array(
					'success' 	=> true,
					'user_id' => $response['user']['id'],
					'user_external_id' => $response['user']['external_id'],
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

	/**
	 * Returns user by user zendesk id
	 *
	 * @param string $userId
	 * @return array
	 */
	public function getById($userId) {
		try {
			$result = array('success' => 0);

			$user = new Users($this->client);
			$user->getUser(array('id' => $userId));

			$response = $user->getResponse();
			if (isset($response['user'])) {
				$this->user = $response['user'];

				$result = array(
					'success' 	=> true,
					'user' => $response['user']
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
				exit;
			}
		} else {
			$this->user['id'] = $userId;
		}

		try {
			$result = array('success' => 0);

			$userTickets = new UserTickets($this->client);
			$userTickets->requested(array('id' => $this->user['id']));

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

				$result = array(
					'success' 				=> true,
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
			exit;
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
			$result = array('success' => 0);

			$user = new Users($this->client);
			$user->search(array('query' => 'email:'.$email));

			$response = $user->getResponse();
			if (isset($response['users'])) {
				$this->user = $response['users'][0];

				$result = array(
					'success' 	=> true,
					'user' => $response['users'][0]
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

	/**
	 * NOTE: only use this method if you don't have a Zendesk user id
	 *		 in that case use countTickets()
	 *
	 * Returns user by external id
	 *
	 * @param string $externalId
	 * @return array
	 */
	public function getByExternalId($externalId) {
		try {
			$result = array('success' => 0);

			$user = new Users($this->client);
			$user->search(array('external_id' => $externalId));

			$response = $user->getResponse();
			if (isset($response['users'])) {
				$this->user = $response['users'][0];

				$result = array(
					'success' 	=> true,
					'user' => $response['users'][0]
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