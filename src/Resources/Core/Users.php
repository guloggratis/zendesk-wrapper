<?php
namespace Zendesk\Resources\Core;
/**
 * We only want to expose end-user data
 * Users.php
 * @author: Marco Martins <mapma@fynskemedier.dk>
 * @since:  05-04-2017
 */

use Zendesk\Resources\ResourceAbstract;

/**
 * Class Users
 * @package Zendesk\Resources\Core
 */
class Users extends ResourceAbstract {

	const CLASS_NAME = __CLASS__;

	/**
	 * @param array $params
	 * @throws \Exception
	 */
	public function createOrUpdate(array $params = array()) {
		$route = $this->getRoute(__FUNCTION__, $params);

		$result = $this->client->post($route, array('user' => $params));

		$this->setResponse($result['response']);
		$this->setStatusCode($result['http_code']);
	}

	/**
	 * Search for users
	 *
	 * @param array $params Accepts `external_id` & `query`
	 * @throws \Exception
	 */
	public function search(array $params = array()) {
		$route = $this->getRoute(__FUNCTION__, $params);

		$result = $this->client->get($route, $params);

		$this->setResponse($result['response']);
		$this->setStatusCode($result['http_code']);
	}

	/**
	 * Gets a user data based on his id
	 *
	 * @param array $params
	 * @throws \Exception
	 */
	public function getUser(array $params = array()) {
		$route = $this->getRoute(__FUNCTION__, $params);

		$result = $this->client->get($route, $params);

		$this->setResponse($result['response']);
		$this->setStatusCode($result['http_code']);
	}

	/**
	 * Deletes a user in Zendesk based on his Zendesk id
	 *
	 * @param array $params
	 * @throws \Exception
	 */
	public function deleteUser(array $params = array()) {
		$route = $this->getRoute(__FUNCTION__, $params);

		$result = $this->client->delete($route, $params);

		$this->setResponse($result['response']);
		$this->setStatusCode($result['http_code']);
	}

	/**
	 * Permanently deletes a user in Zendesk based on his Zendesk id
	 *
	 * @param array $params
	 * @throws \Exception
	 */
	public function permanentlyDeleteUser(array $params = array()) {
		$route = $this->getRoute(__FUNCTION__, $params);

		$result = $this->client->delete($route, $params);

		$this->setResponse($result['response']);
		$this->setStatusCode($result['http_code']);
	}

	/**
	 * Declares routes to be used by this resource.
	 */
	protected function setUpRoutes() {
		parent::setUpRoutes();

		$this->setRoutes(array(
			'createOrUpdate'        => 'users/create_or_update.json',
			'search'             	=> 'users/search.json',
			'getUser'  				=> 'users/{id}.json',
			'deleteUser'  			=> 'users/{id}.json',
			'permanentlyDeleteUser' => 'deleted_users/{id}.json',
		));
	}
}