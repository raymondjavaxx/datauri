<?php
/**
 * DataURI
 *
 * Copyright (c) 2012 Ramon Torres
 *
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) 2012 Ramon Torres
 * @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace datauri;

/**
 * "data" URI Parser
 *
 * @package datauri
 */
class Parser {

	public static function parse($dataURI) {
		if (strpos($dataURI, 'data:') !== 0) {
			throw new Exception('URI is not a data URI');
		}

		$parsedURI = static::_parse($dataURI);

		$data = new Data();
		if (isset($parsedURI[0])) {
			$data->contentType = $parsedURI[0];
		}

		if ($parsedURI['base64']) {
			$data->content = base64_decode($parsedURI['data'], true);
		} else {
			$data->content = rawurldecode($parsedURI['data']);
		}

		if (isset($parsedURI['charset'])) {
			$data->charset = $parsedURI['charset'];
		}

		if ($data->content === false) {
			throw new Exception('Failed to decode data');
		}

		return $data;
	}

	public static function _parse($data) {
		$data = str_replace(array("\r\n", "\n", "\r", "data:"), '', $data);

		$parts = explode(',', $data, 2);
		if (count($parts) !== 2) {
			throw new Exception("DataURI is missing <data> part");
		}

		$parsedURI = array(
			'base64' => false,
			'data' => $parts[1]
		);

		$token = strtok($parts[0], ';');
		while ($token !== false) {
			if (strpos($token, '=') !== false) {
				list($k, $v) = explode('=', $token, 2);
				$parsedURI[$k] = $v;
			} else if ($token == 'base64') {
				$parsedURI['base64'] = true;
			} else {
				$parsedURI[] = $token;
			}

			$token = strtok(';');
		}

		return $parsedURI;
	}
}
