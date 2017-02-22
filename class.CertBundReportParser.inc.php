<?php

	/**
	 * A parser for CERT BUND report mails
	 */
	class CertBundReportParser {

		const REGEX_MAIL_FORMAT_PARSE   = '/Format:(?<format>[^\n]*)(?<data>.*)Mit freundlichen/s';
		const REGEX_MAIL_FORMAT_REPLACE = '/Format:(.*)(Mit freundlichen)/s';
		const ANONYMIZATION_TEXT =
"****************************************
* CENSORED (original report data here) *
****************************************
";

		/**
		 * Parses a CERT BUND report mail body
		 *
		 * @param $body The mail body as string, headers are optional
		 * @return The parsed mail as array, one line for each incident, FALSE in case of error
		 */
		public static function parse($body) {

			// Sanity Checks
			if (is_null($body) || !is_string($body) || !strlen($body)) {
				return FALSE;
			}

			// Preparations
			$body = str_replace("\r", '', $body);
			$body = str_replace("\t", '', $body);
			$body = trim($body);

			// Check mail format
			if (!preg_match(self::REGEX_MAIL_FORMAT_PARSE, $body, $matches)) {
				return FALSE;
			}

			// Parse format specification
			$format = [];

			foreach (explode('|', trim($matches['format'])) as $column) {
				$format[] = trim($column);
			}

			if (!count($format)) {
				return FALSE;
			}

			// Parse data (incident lines)
			$result = [];

			foreach (explode("\n", trim($matches['data'])) as $line) {

				$line = trim($line);
				if (!$line || !strlen($line)) {
					continue;
				}

				$incident = [];
				foreach (explode('|', $line) as $column) {
					$incident[] = trim($column);
				}

				$incident = array_combine($format, $incident);
				if ($incident === FALSE) {
					continue;
				}

				$result[] = $incident;

			}

			return $result;

		}


		/**
		 * Anonymizes a CERT BUND report mail body
		 *
		 * @param $body The mail body as string, headers are optional
		 * @param $body The string to be used as replacement
		 * @return The mail body anonymized with all incidents removed, FALSE in case of error
		 */
		public static function anonymize($body, $replacement=NULL) {

			// Sanity Checks
			if (is_null($body) || !is_string($body) || !strlen($body)) {
				return FALSE;
			}

			// Check mail format
			if (!preg_match(self::REGEX_MAIL_FORMAT_PARSE, $body, $matches)) {
				return FALSE;
			}

			// Go
			$result = preg_replace(self::REGEX_MAIL_FORMAT_REPLACE, $replacement ?: self::ANONYMIZATION_TEXT."\n".'$2', $body);

			return $result;

		}

	}

?>
