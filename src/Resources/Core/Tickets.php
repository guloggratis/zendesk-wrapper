<?php
namespace Zendesk\Resources\Core;
/**
 * Tickets.php
 * @author: Marco Martins <mapma@fynskemedier.dk>
 * @since:  06-04-2017
 */

use Zendesk\Resources\ResourceAbstract;

/**
 * Class Tickets
 * @package Zendesk\Resources\Core
 */
class Tickets extends ResourceAbstract {

	const CLASS_NAME = __CLASS__;

	/**
	 * Create a ticket
	 *
	 * @param array $params
	 * @throws \Exception
	 */
	public function create(array $params = array()) {
		$extraOptions = array();

		if (isset($params['async']) && ($params['async'] == true)) {
			$extraOptions = array(
				'queryParams' => array(
					'async' => true
				)
			);
		}

		$route = $this->getRoute(__FUNCTION__, $params);

		$result = $this->client->post($route, array('ticket' => $params), $extraOptions);

		$this->setResponse($result['response']);
		$this->setStatusCode($result['http_code']);
	}


	/**
	 * Declares routes to be used by this resource.
	 */
	protected function setUpRoutes() {
		parent::setUpRoutes();

		$this->setRoutes(array(
			'create'	=> 'tickets.json'
		));
	}
}