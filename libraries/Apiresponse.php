<?php
/**
 * ApiResponse formats the given data into a JSON response content.
 */
class ApiResponse {
	/**
	 * @type boolean
	 */
	private $success;
	/**
	 * @type array
	 */
	private $errors;
	/**
	 * @type array
	 */
	private $messages;
	/**
	 * @type array
	 */
	private $response;

	public function __construct() {
		$this->Reset();
	}

	/**
	 * @param string $value
	 */
	public function addError($value) {
		$this->errors[] = (string)$value;
	}

	/**
	 * @param string $value
	 */
	public function addMessage($value) {
		$this->messages[] = (string)$value;
	}

	/**
	 * @return array
	 */
	public function getErrors() {
		return $this->errors;
	}
	
	/**
	 * @return array
	 */
	public function getMessages() {
		return $this->messages;
	}

	/**
	 * @return mixed
	 */
	public function getData($key) {
		if (isset($this->response[$key])) {
			return $this->response[$key];
		}
		return null;
	}

	/**
	 * @return array
	 */
	public function getResponse() {
		return $this->response;
	}

	/**
	 * Reset response
	 */
	public function Reset() {
		$this->success = false;
		$this->errors = [];
		$this->messages = [];
		$this->response = [];
	}

	public function setSuccess($value = true) {
		$this->success = (bool)$value;
	}

	public function addData($key, $value) {
		if (in_array($key, ['success', 'errors', 'messages']) || !is_string($key) || strlen($key) == 0) {
			return false;
		}
		$this->response[$key] = $value;
	}

	public function setData($key, $value) {
		if (in_array($key, ['success', 'errors', 'messages']) || !is_string($key) || strlen($key) == 0) {
			return false;
		}
		$this->response[$key] = $value;
	}

	/**
	 * @return boolean
	 */
	public function isSuccess() {
		return $this->success;
	}

	private function prepareResponse() {
		$this->response['success'] = (bool)$this->success;
		$this->response['errors'] = (array)$this->errors;
		$this->response['messages'] = (array)$this->messages;
	}

	public function getJSON() {
		$this->prepareResponse();
		return json_encode($this->response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	}

	public function fromJSON($text) {
		$this->Reset();
		if (is_string($text)) {
			$response = json_decode($text);
			if (is_object($response)) {
				$responseVars = get_object_vars($response);
				foreach ($responseVars as $key => $var) {
					if ($key == 'success') {
						$this->setSuccess((bool)$var);
					} elseif ($key == 'errors' && is_array($var)) {
						foreach ($var as $error) {
							$this->addError($error);
						}
					} elseif ($key == 'messages' && is_array($var)) {
						foreach ($var as $message) {
							$this->addMessage($message);
						}
					} else {
						$this->setData($key, $var);
					}
				}
			}
		}
	}

}
