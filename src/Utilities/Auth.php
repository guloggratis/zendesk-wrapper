<?php
namespace Zendesk\Utilities;
/**
 * Auth.php
 * @author: Marco Martins <mapma@fynskemedier.dk>
 * @since:  05-04-2017
 */

/**
 * Class Auth
 * @package Zendesk\Utilities
 */
class Auth {

	/**
	 * The authentication setting to use Basic authentication with a username and API Token.
	 */
	const BASIC = 'basic';

	/**
	 * @var string
	 */
	protected $authStrategy;

	/**
	 * @var array
	 */
	protected $authOptions;

	/**
	 * Returns an array containing the valid auth strategies
	 *
	 * @return array
	 */
	protected static function getValidAuthStrategies() {
		return array(self::BASIC);
	}

	/**
	 * Auth constructor.
	 *
	 * @param string 	$strategy
	 * @param array 	$options
	 *
	 * @throws \Exception
	 */
	public function __construct($strategy, $options = array()) {
		if (! in_array($strategy, self::getValidAuthStrategies())) {
			throw new \Exception('Invalid auth strategy set, please use `' . implode('` or `', self::getValidAuthStrategies()) . '`');
		}

		$this->authStrategy = $strategy;

		if ($strategy == self::BASIC) {
			if (!array_key_exists('username', $options) || ! array_key_exists('token', $options)) {
				throw new \Exception('Please supply `username` and `token` for basic auth.');
			}
		}

		$this->authOptions = $options;
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	public function prepareRequest() {
		if ($this->authStrategy === self::BASIC) {
			$requestOptions = array(
				$this->authOptions['username'] . '/token:',
				$this->authOptions['token']
			);
		} else {
			throw new \Exception('Please set authentication to send requests.');
		}
		return $requestOptions;
	}
}