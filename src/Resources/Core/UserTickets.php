<?php
namespace Zendesk\Resources\Core;
/**
 * UserTickets.php
 * @author: Marco Martins <mapma@fynskemedier.dk>
 * @since:  20-04-2017
 */

use Zendesk\Resources\ResourceAbstract;

/**
 * Class UserTickets
 * @package Zendesk\Resources\Core
 */
class UserTickets extends ResourceAbstract {

	const CLASS_NAME = __CLASS__;

	/**
	 * List tickets that a user requested
	 *
	 * @param array $params
	 * @return \stdClass
	 * @throws \Exception
	 */
	public function requested(array $params = array()) {
		$params = $this->addChainedParametersToParams($params, array('id' => Users::CLASS_NAME));

		if (!$this->hasKeys($params, array('id'))) {
			throw new \Exception(__METHOD__ . ' -> To request user tickets provide parameter ID');
		}

		$route = $this->getRoute(__FUNCTION__, $params);

		$result = $this->client->get($route, $params);

		$this->setResponse($result['response']);
		$this->setStatusCode($result['http_code']);
	}

	/**
	 * Declares routes to be used by this resource.
	 */
	protected function setUpRoutes() {
		parent::setUpRoutes();

		$this->setRoutes(array(
			'requested' => 'users/{id}/tickets/requested.json',
		));
	}
}