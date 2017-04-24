<?php
/**
 * HttpClient.php
 * @author: Marco Martins <mapma@fynskemedier.dk>
 * @since:  05-04-2017
 */
namespace Zendesk;

use Zendesk\Utilities\Auth;

use Zendesk\Resources\Core\Tickets;
use Zendesk\Resources\Core\Users;
/**
 * Class HttpClient
 * @package Zendesk
 */
class HttpClient {

	/**
	 * Version
	 */
	const VERSION = '1.0';

	/**
	 * @var Auth
	 */
	protected $auth;
	/**
	 * @var string
	 */
	protected $subdomain;
	/**
	 * @var string
	 */
	protected $scheme;
	/**
	 * @var string
	 */
	protected $hostname;
	/**
	 * @var integer
	 */
	protected $port;
	/**
	 * @var string
	 */
	protected $apiUrl;
	/**
	 * @var string This is appended between the full base domain and the resource endpoint
	 */
	protected $apiBasePath;

	/**
	 * @param string $subdomain
	 * @param string $username
	 * @param string $scheme
	 * @param string $hostname
	 * @param int $port
	 */
	public function __construct($subdomain, $username = '', $scheme = "https", $hostname = "zendesk.com", $port = 443) {
		$this->subdomain = $subdomain;
		$this->hostname  = $hostname;
		$this->scheme    = $scheme;
		$this->port      = $port;

		if (empty($subdomain)) {
			$this->apiUrl = "$scheme://$hostname:$port/";
		} else {
			$this->apiUrl = "$scheme://$subdomain.$hostname:$port/";
		}
	}

	/**
	 * @return Auth
	 */
	public function getAuth() {
		return $this->auth;
	}

	/**
	 * * Configure the authorization method
	 *
	 * @param       $strategy
	 * @param array $options
	 *
	 * @throws \Exception
	 */
	public function setAuth($strategy, $options = array()) {
		$this->auth = new Auth($strategy, $options);
	}

	/**
	 * Return the user agent string
	 *
	 * @return string
	 */
	public function getUserAgent() {
		return 'ZendeskAPI PHP ' . self::VERSION;
	}

	/**
	 * Returns the supplied subdomain
	 *
	 * @return string
	 */
	public function getSubdomain() {
		return $this->subdomain;
	}

	/**
	 * Returns the generated api URL
	 *
	 * @return string
	 */
	public function getApiUrl() {
		return $this->apiUrl;
	}

	/**
	 * Sets the api base path
	 *
	 * @param string $apiBasePath
	 */
	public function setApiBasePath($apiBasePath) {
		$this->apiBasePath = $apiBasePath;
	}

	/**
	 * Returns the api base path
	 *
	 * @return string
	 */
	public function getApiBasePath() {
		return $this->apiBasePath;
	}

	/**
	 * This is a helper method to do a get request.
	 *
	 * @param       $endpoint
	 * @param array $queryParams
	 *
	 * @return \stdClass
	 * @throws \Exception
	 */
	public function get($endpoint, $queryParams = array()) {
		$response = Http::send($this, $endpoint, array('queryParams' => $queryParams));

		return $response;
	}

	/**
	 * This is a helper method to do a post request.
	 *
	 * @param       $endpoint
	 * @param array $postData
	 * @param array $options
	 *
	 * @return \stdClass
	 * @throws \Exception
	 */
	public function post($endpoint, $postData = array(), $options = array()) {
		$extraOptions = array_merge($options, array('postFields' => $postData, 'method' => 'POST'));

		$response = Http::send($this, $endpoint, $extraOptions);

		return $response;
	}

	/**
	 * This is a helper method to do a put request.
	 *
	 * @param       $endpoint
	 * @param array $putData
	 *
	 * @return \stdClass
	 * @throws \Exception
	 */
	public function put($endpoint, $putData = array()) {
		$response = Http::send($this, $endpoint, array('postFields' => $putData, 'method' => 'PUT'));

		return $response;
	}

	/**
	 * This is a helper method to do a delete request.
	 *
	 * @param $endpoint
	 *
	 * @return null
	 * @throws \Exception
	 */
	public function delete($endpoint) {
		$response = Http::send($this, $endpoint, array('method' => 'DELETE'));

		return $response;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return array
	 */
	public static function getValidSubResources() {
		return array(
			'tickets'	=> Tickets::CLASS_NAME,
			'users'     => Users::CLASS_NAME,
		);
	}
}