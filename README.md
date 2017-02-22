# CERT BUND report paser
A PHP parser for german CERT BUND report mails

# Usage

## Parse a mail
To parse a mail:

		$incidents = CertBundReportParser::parse( [BODYGOESHERE] );
		if ($incidents === FALSE) {
			return FALSE;
		}

Each element of the returned array contains one incident line from the original mail from CERT BUND, the array keys are the columns, e.g.

	array(2) {
	  [0] =>
	  array(5) {
	    'ASN' =>
	    string(5) "123456789"
	    'IP address' =>
	    string(11) "1.2.3.4"
	    'Timestamp (UTC)' =>
	    string(19) "2017-02-22 09:14:00"
	    'Foo' =>
	    string(5) "Bar"
	    'Bar' =>
	    string(6) "Foo"
	  }
	  [1] =>
	  array(5) {
	    'ASN' =>
	    string(5) "123456789"
	    'IP address' =>
	    string(11) "2.3.4.5"
	    'Timestamp (UTC)' =>
	    string(19) "2017-02-22 09:14:00"
	    'Foo' =>
	    string(5) "Bar"
	    'Bar' =>
	    string(6) "Foo"
	  }
	}

## Anonymizing a mail
To anonymize a mail (e.g. for forwarding):

		$body_anonymized = CertBundReportParser::anonymize( [BODYGOESHERE] );
		if ($body_anonymized === FALSE) {
			return FALSE;
		}
		
The body will be returned with all relevant data be anonymized and replaced by
 
	****************************************
	* CENSORED (original report data here) *
	****************************************

The replacement text can be optionally passed to `CertBundReportParser::anonymize()`.
