<?php
namespace Zendesk\Resources\Core;
/**
 * Attachments.php
 * @author: Marco Martins <mapma@fynskemedier.dk>
 * @since:  21-04-2017
 */

use Zendesk\Resources\ResourceAbstract;

/**
 * Class Attachments
 * @package Zendesk\Resources\Core
 */
class Attachments extends ResourceAbstract {

	const CLASS_NAME = __CLASS__;


	public function upload(array $params = array()) {
		if (!$this->hasKeys($params, array('file'))) {
			throw new \Exception(__METHOD__ . ' Missing parameter "file"');
		} elseif (!file_exists($params['file'])) {
			throw new \Exception('File ' . $params['file'] . ' could not be found in ' . __METHOD__);
		}

		if (!isset($params['name'])) {
			$params['name'] = basename($params['file']);
		}

		$queryParams = array('queryParams' => array(
			'filename' => $params['name']
		));

		$route = $this->getRoute(__FUNCTION__, $params);

		$result = $this->client->post(
			$route,
			array(
				'file' => $params['file'],
				'contentType' => 'application/binary'
			), $queryParams);

		$this->setResponse($result['response']);
		$this->setStatusCode($result['http_code']);
	}

	/**
	 * Declares routes to be used by this resource.
	 */
	protected function setUpRoutes() {
		parent::setUpRoutes();

		$this->setRoutes(array(
			'upload' => "uploads.json",
		));
	}
}