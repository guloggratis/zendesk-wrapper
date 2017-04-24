<?php
namespace Zendesk\Resources;
/**
 * ResourceAbstract.php
 * @author: Marco Martins <mapma@fynskemedier.dk>
 * @since:  05-04-2017
 */

use Zendesk\HttpClient;

/**
 * Class ResourceAbstract
 * @package Zendesk\Resources
 */
abstract class ResourceAbstract {

	/**
	 * @var String
	 */
	protected $resourceName;

	/**
	 * @var String
	 */
	protected $objectName;

	/**
	 * @var HttpClient
	 */
	protected $client;

	/**
	 * @var array
	 */
	protected $routes = array();

	/**
	 * @var array
	 */
	protected $additionalRouteParams = array();

	/**
	 * @var string
	 */
	protected $apiBasePath = 'api/v2/';

	/**
	 * @var int
	 */
	protected $statusCode = 0;

	/**
	 * @var array
	 */
	protected $response = array();

	/**
	 * @param HttpClient $client
	 */
	public function __construct(HttpClient $client) {
		$this->client = $client;
		$this->client->setApiBasePath($this->apiBasePath);

		if (!isset($this->resourceName)) {
			$this->resourceName = $this->getResourceNameFromClass();
		}

		if (!isset($this->objectName)) {
			$this->objectName = $this->resourceName;
		}

		$this->setUpRoutes();
	}

	/**
	 * This returns the valid relations of this resource. Definition of what is allowed to chain after this resource.
	 * Make sure to add in this method when adding new sub resources.
	 * Example:
	 *    $client->ticket()->comments();
	 *    Where ticket would have a comments as a valid sub resource.
	 *    The array would look like:
	 *      array('comments' => '\Zendesk\API\Resources\TicketComments')
	 *
	 * @return array
	 */
	public static function getValidSubResources() {
		return array();
	}

	/**
	 * Return the resource name using the name of the class (used for endpoints)
	 *
	 * @return string
	 */
	protected function getResourceNameFromClass() {
		$namespacedClassName = get_class($this);
		$resourceName        = join('', array_slice(explode('\\', $namespacedClassName), -1));

		// This converts the resource name from camel case to underscore case.
		// e.g. MyClass => my_class
		$underscored = strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $resourceName));

		return strtolower($underscored);
	}

	/**
	 * @return String
	 */
	public function getResourceName() {
		return $this->resourceName;
	}

	/**
	 * Sets up the available routes for the resource.
	 */
	protected function setUpRoutes() { }

	/**
	 * Check that all parameters have been supplied
	 *
	 * @param array $params
	 * @param array $mandatory
	 *
	 * @return bool
	 */
	public function hasKeys(array $params, array $mandatory) {
		for ($i = 0; $i < count($mandatory); $i++) {
			if (!array_key_exists($mandatory[$i], $params)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Wrapper for adding multiple routes via setRoute
	 *
	 * @param array $routes
	 */
	public function setRoutes(array $routes) {
		foreach ($routes as $name => $route) {
			$this->setRoute($name, $route);
		}
	}

	/**
	 * Add or override an existing route
	 *
	 * @param $name
	 * @param $route
	 */
	public function setRoute($name, $route) {
		$this->routes[$name] = $route;
	}

	/**
	 * Return all routes for this resource
	 *
	 * @return array
	 */
	public function getRoutes() {
		return $this->routes;
	}

	/**
	 * Returns a route and replaces tokenized parts of the string with
	 * the passed params
	 *
	 * @param       $name
	 * @param array $params
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function getRoute($name, array $params = array()) {
		if (! isset($this->routes[$name])) {
			throw new \Exception('Route not found.');
		}

		$route = $this->routes[$name];

		$substitutions = array_merge($params, $this->getAdditionalRouteParams());
		foreach ($substitutions as $name => $value) {
			if (is_scalar($value)) {
				$route = str_replace('{' . $name . '}', $value, $route);
			}
		}

		return $route;
	}

	/**
	 * @param array $additionalRouteParams
	 */
	public function setAdditionalRouteParams($additionalRouteParams) {
		$this->additionalRouteParams = $additionalRouteParams;
	}

	/**
	 * @return array
	 */
	public function getAdditionalRouteParams() {
		return $this->additionalRouteParams;
	}

	/**
	 * @return int
	 */
	public function getStatusCode() {
		return $this->statusCode;
	}

	/**
	 * @param int $code
	 */
	public function setStatusCode($code) {
		$this->statusCode = $code;
	}

	/**
	 * Will have the curl response or the exception errors
	 * @return array
	 */
	public function getResponse() {
		return $this->response;
	}

	/**
	 * @param array $response
	 */
	public function setResponse($response) {
		$this->response = $response;
	}



	/**  From here on is the Chained Parameters  **/

	/**
	 * @var array
	 */
	protected $chainedParameters = array();

	/**
	 * Returns the named chained parameter
	 *
	 * @param      $name
	 * @param null $default
	 *
	 * @return $this
	 */
	public function getChainedParameter($name, $default = null) {
		$chainedParameters = $this->getChainedParameters();
		if (array_key_exists($name, $chainedParameters)) {
			return $chainedParameters[$name];
		}

		return $default;
	}

	/**
	 * Returns chained parameters
	 * @return array
	 */
	public function getChainedParameters() {
		return $this->chainedParameters;
	}

	/**
	 * Sets the chained parameters
	 *
	 * @param $params
	 *
	 * @return $this
	 */
	public function setChainedParameters($params) {
		$this->chainedParameters = $params;

		return $this;
	}

	/**
	 * A helper method to add the chained parameters to the existing parameters.
	 *
	 * @param array $params The existing parameters
	 * @param array $map    An array describing what parameter key corresponds to which classId
	 *                      e.g. ['ticket_id' => 'Zendesk\API\Ticket']
	 *                      normal usage would be ['id' => $this::class]
	 *
	 * @return array
	 */
	public function addChainedParametersToParams($params, $map) {
		$chainedParameters = $this->getChainedParameters();
		foreach ($map as $key => $className) {
			if (array_key_exists($className, $chainedParameters)) {
				$params[$key] = $chainedParameters[$className];
			}
		}

		return $params;
	}











}