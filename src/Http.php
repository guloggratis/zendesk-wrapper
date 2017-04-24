<?php
/**
 * Http.php
 * @author: Marco Martins <mapma@fynskemedier.dk>
 * @since:  05-04-2017
 */
namespace Zendesk;

/**
 * HTTP functions via curl
 * Class Http
 * @package Zendesk
 */
class Http {

	/**
	 * Use the send method to call every endpoint except for oauth/tokens
	 *
	 * @param HttpClient $client
	 * @param string     $endPoint E.g. "/tickets.json"
	 * @param array      $options
	 *                             Available options are listed below:
	 *                             array $queryParams Array of unencoded key-value pairs, e.g. ["ids" => "1,2,3,4"]
	 *                             array $postFields Array of unencoded key-value pairs, e.g. ["filename" => "blah.png"]
	 *                             string $method "GET", "POST", etc. Default is GET.
	 *                             string $contentType Default is "application/json"
	 *
	 * @return array The response body, parsed from JSON into an object. Also returns null if something went wrong
	 * @throws \Exception
	 */
	public static function send(HttpClient $client, $endPoint, $options = array()) {
		$options = array_merge(
			array(
				'method'      => 'GET',
				'postFields'  => null,
				'queryParams' => null
			),
			$options
		);

		$uri = $client->getApiUrl() . $client->getApiBasePath() . $endPoint;

		$header = array("Content-Type: application/json");
		if (isset($options['postFields']['contentType'])) {
			$header = array("Content-Type: " . $options['postFields']['contentType']);
		}

		if (!empty($options['queryParams'])) {
			foreach($options['queryParams'] as $queryKey => $queryValue) {
				$uri .= '?' . $queryKey . '=' . $queryValue;
			}
		}

		try {
			$session = curl_init($uri);

			if ($options['method'] == 'POST') {
				curl_setopt($session, CURLOPT_POST, 1);
			} else {
				curl_setopt($session, CURLOPT_CUSTOMREQUEST, $options['method']);
			}

			curl_setopt($session, CURLOPT_USERPWD, implode($client->getAuth()->prepareRequest()));

			if (isset($options['postFields']['file'])) {
				$postData = file_get_contents($options['postFields']['file'], 'r');
				curl_setopt($session, CURLOPT_POSTFIELDS, $postData);
			} else {
				curl_setopt($session, CURLOPT_POSTFIELDS, json_encode($options['postFields']));
			}

			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($session, CURLOPT_HTTPHEADER, $header);
			curl_setopt($session, CURLOPT_CONNECTTIMEOUT ,0);
			curl_setopt($session, CURLOPT_TIMEOUT, 400); //timeout in seconds

			$response 	= curl_exec($session);
			$error      = curl_error($session);
			$http_code  = curl_getinfo($session ,CURLINFO_HTTP_CODE);

			curl_close($session);
		} catch (\Exception $e) {
			$response = $e->getMessage();
			$http_code = $e->getCode();
		}

		$result = array(
			'response' => json_decode($response, 1),
			'http_code' => $http_code
		);

		return $result;
	}
}