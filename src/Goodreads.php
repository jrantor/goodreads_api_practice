<?php 

class Goodreads{

	const ROOT_URL = "https://www.goodreads.com";

	protected $api_key = '';

	public function __construct($api_key){
		$this->api_key = (string)$api_key;
	}

	public function search_with_query($query){
		return $this->request('search',array(
			'key' => $this->api_key,
			'q' => $query
		));
	}

	public function get_review($title,$author){
		return $this->request(
			'book/title', array(
				'key' => $this->api_key,
				'title' => $title,
				'author' => $author
			)
		);
	}

	public function get_books_by_author($id){
		return $this->request('author/list', array(
			'key' => $this->api_key,
			'id' => $id,
		));
	}


	private function request($endpoint,$params){
		$url = self::ROOT_URL.'/'.$endpoint.'?'.((!empty($params)) ? http_build_query($params,'','&') : '');
		$headers = array(
			'Accept: application/xml'
		);

		if(isset($params['format']) && $params['format'] === 'json'){
			$headers = array(
				'Accept: application/json'

			);
		}

		if(extension_loaded('curl')){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
		
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($ch);
			$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			$header = substr($response, 0,$header_size);
			$result = substr($response, $header_size);
			$error_no = curl_errno($ch);
			$error_message = curl_error($ch);

			if($error_no > 0) throw new Exception("Error Processing Request on ".$endpoint." ".$error_message);	

			 curl_close($ch);	
		} else{
			throw new Exception("Error Processing curl library.");
			
		}

		if(isset($params['format']) && $params['format'] === 'json'){
			$result = json_decode($result);
		}

		else{
			$result = json_decode(json_encode((array)simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA)),1);
		}

		if(!empty($result)) return $result;
		else throw new Exception("Error Getting Results");
	
	}
}

?>