<?php

$constants = [
    'CURLE_SSL_CERTPROBLEM' => 58,
    'CURLE_SSL_CACERT' => 60,
    'CURLE_SSL_CACERT_BADFILE' => 77,
    'CURLE_SSL_CRL_BADFILE' => 82,
    'CURLE_SSL_ISSUER_ERROR' => 83
];
foreach ($constants as $id => $i) {
    defined($id) or define($id, $i);
}
unset($constants);
unset($i);
unset($id);

class Force24API {

	// API End Point
	protected static $_endpoint 	= 'https://alpha-api.data-crypt.com';

	// API Username
	protected static $_username 	= '';
	
	// API Password
	protected static $_password 	= '';

	// Cookie Jar path
	protected static $_token 		= '';

	// Script version
	protected static $_version 		= '1.0.0';

	// Sets username and password
	public function setLogin($username, $password) {
		self::$_username = $username; 
		self::$_password = $password; 
	}
	
	public function setToken($token)
	{
		self::$_token = $token;
	}

	// Gets the current version
	public function getVersion() {
        return self::$_version;
    }

    // Gets the client id
    public function getPersonId($guid) {
    	
    	$ch = $this->getCurlInitialization(self::$_endpoint.'/api/contacts/id/'.$guid);

    	$result = curl_exec($ch);

    	// catch request errors
        if ($result === false) {
            throw new \Exception('Request error for API call: ' . curl_error($ch));
        }

    	list($header, $reply) = explode("\r\n\r\n", $result, 2);
		
		$reply = json_decode($reply, true);

    	return $reply;
    }

    // Gets the contact info
    public function getContactInfo($id) {
			
		$ch = $this->getCurlInitialization(self::$_endpoint.'/api/contact/'.$id);

		$result = curl_exec($ch);

		list($header, $reply) = explode("\r\n\r\n", $result, 2);

		$reply = json_decode($reply, true);

    	return $reply;
    }
    
    public function getContactsByEmail($email) {
	    
	    $ch = $this->getCurlInitialization(self::$_endpoint.'/api/contacts/email/'.$email.'/');
		$result = curl_exec($ch);

		list($header, $reply) = explode("\r\n\r\n", $result, 2);

		$reply = json_decode($reply, true);

    	return $reply;
    }
    
    public function createContact($contact) {
	    
	    $params = http_build_query($contact);
		
		$request_headers['Content-Length'] = 'Content-Length: '.strlen($params);
		
	    $ch = $this->getCurlInitialization(self::$_endpoint.'/api/contact');
        curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);

		$result = curl_exec($ch);

		list($header, $reply) = explode("\r\n\r\n", $result, 2);

		$reply = json_decode($reply, true);

    	return $reply;
    }
    
    public function updateContact($pid, $contact) {
	    
	    $params = json_encode($contact);
	    
		$request_headers['Content-Length'] 	= 'Content-Length: '.strlen($params);
		$request_headers['Content-type'] 	= 'Content-type: application/json';

	    $ch = $this->getCurlInitialization(self::$_endpoint.'/api/contact/'.$pid.'/put', $request_headers);
	    
		curl_setopt($ch, CURLOPT_HEADER, false);         
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		$reply = curl_exec($ch);
		die($reply);
		
		$reply = json_decode($reply, true);
		

    	return $reply;
    }
    
    public function upsertContact($contact, $type='email') {
	    
    }

	// Do the authentication and set the cookie
	public function getToken() {
		$params = array(
			'username' => self::$_username,
			'password' => self::$_password,
			'grant_type' => 'password'
		);
		
		$params = http_build_query($params);
		
		$request_headers['Content-Length'] = 'Content-Length: '.strlen($params);

		$ch = $this->getCurlInitialization(self::$_endpoint.'/token');
        curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);

		$result = curl_exec($ch);

        // catch request errors
        if ($result === false) {
            throw new \Exception('Request error for API call: ' . curl_error($ch));
        }

        // certificate validation results
        $validation_result = curl_errno($ch);
        $this->_validateSslCertificate($validation_result);
		
		$httpstatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		list($header, $reply) = explode("\r\n\r\n", $result, 2);
		
		$reply = json_decode($reply, true);
		
		return $reply;
		
	}

    // Validate guid
    public function validateGuid($guid) {
        if(preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', strtoupper($guid))) {
            return true;
        } else {
            return false;
        }
    }

	// Gets a cURL handle
	protected function getCurlInitialization($url, $request_headers=array()) {
		$ch = curl_init($url);
		$f = fopen('request.txt', 'w');
		curl_setopt($ch,CURLOPT_VERBOSE,true);
		curl_setopt($ch,CURLOPT_STDERR ,$f);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        //curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . '/cacert.pem');
        curl_setopt(
            $ch, CURLOPT_USERAGENT,
            'force24-php/' . $this->getVersion()
        );
        
        if(!isset($request_headers['Content-Type'])) {
        	$request_headers['Content-Type'] = 'Content-Type: application/x-www-form-urlencoded';
        }
        if(self::$_token) {
    		$request_headers[] = 'Authorization: Bearer ' . self::$_token;
    	}
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
		
		return $ch;
	}

	// Check if there were any SSL certificate errors
	protected function _validateSslCertificate($validation_result) {
        if (in_array(
                $validation_result,
                [
                    CURLE_SSL_CERTPROBLEM,
                    CURLE_SSL_CACERT,
                    CURLE_SSL_CACERT_BADFILE,
                    CURLE_SSL_CRL_BADFILE,
                    CURLE_SSL_ISSUER_ERROR
                ]
            )
        ) {
            throw new \Exception(
                'Error ' . $validation_result
                . ' while validating the API certificate.'
            );
        }
    }

}