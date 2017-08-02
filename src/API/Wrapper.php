<?php
namespace Zendesk\API;
/**
 * Wrapper.php
 * @author: Marco Martins <mapma@fynskemedier.dk>
 * @since:  06-04-2017
 */
use Zendesk\HttpClient;

/**
 * Class Wrapper
 * @package Zendesk\API
 */
class Wrapper {

	/**
	 * @var HttpClient
	 */
	protected $client;

	/**
	 * @throws \Exception
	 */
	public function __construct() {
		if (file_exists(dirname(__FILE__) . '/../../config/zendesk.php')) {
			$configuration = include(dirname(__FILE__) . '/../../config/zendesk.php');
		} else {
			echo 'You need the configuration file /config/zendesk.php' . PHP_EOL;
			return false;
		}

		$this->client = new HttpClient($configuration['subdomain']);
		$this->client->setAuth('basic', array('username' => $configuration['username'], 'token' => $configuration['token']));
	}


	/**
	 * Validates the user fields
	 *
	 * @param array $userFields
	 * @return bool
	 */
	public function validateUserFields($userFields = array()) {
		if (!isset($userFields["name"]) || empty($userFields["name"])) {
			return false;
		}
		if (!isset($userFields["email"]) || empty($userFields["email"])) {
			return false;
		}
		if (!filter_var($userFields["email"], FILTER_VALIDATE_EMAIL)) {
			return false;
		}
		return true;
	}


	/**
	 * Validates the ticket fields
	 *
	 * @param array $ticketFields
	 * @return bool
	 */
	public function validateTicketFields($ticketFields = array()) {
		if (!isset($ticketFields["type"]) || empty($ticketFields["type"])) {
			return false;
		}
		if (!isset($ticketFields["subject"]) || empty($ticketFields["subject"])) {
			return false;
		}
		if (!isset($ticketFields["comment"]) || empty($ticketFields["comment"])) {
			return false;
		}
		if (!isset($ticketFields["comment"]["body"]) || empty($ticketFields["comment"]["body"])) {
			return false;
		}
		if (!isset($ticketFields["requester"]) || empty($ticketFields["requester"])) {
			return false;
		}
		return true;
	}
}