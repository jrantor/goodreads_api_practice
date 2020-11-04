<?php 

class Goodreads{

    // main url for api

	const ROOT_URL = "https://www.goodreads.com";

	// initializing apikey var

	protected $api_key = '';

	// getting apikey during object creation
	public function __construct($api_key){
		$this->api_key = (string)$api_key;
	}

	/*
	 * Searching books with title, isbn, or author
	 * 
	 * @param string $query Search keyword
	 * 
	 * @return array
	 *
	 */

	public function search_with_query($query){
		return $this->request('search',array(
			'key' => $this->api_key,
			'q' => $query
		));
	}

	/*
	 * Get reviews of a given title & author name
	 *
	 * @param string $title Title of the book
	 *
	 * @param string $author Author's name of the book
	 *
	 * @return array
	 *
	 */

	public function get_review($title,$author){
		return $this->request(
			'book/title', array(
				'key' => $this->api_key,
				'title' => $title,
				'author' => $author
			)
		);
	}

	/*
	 * Get books by an author given author id
	 * 
	 * @param integer $id Author's ID
	 * 
	 * @return array
	 *
	 */

	public function get_books_by_author($id){
		return $this->request('author/list', array(
			'key' => $this->api_key,
			'id' => (int)$id,
		));
	}

	/*
	 * Sending request to specified endpoint and getting response
	 *
	 * @param string $endpoint API endpoint
	 *
	 * @param array $params API endpoint parameters
	 *
	 * @throws exception
	 *
	 * @return array
	 *
	 */

	private function request($endpoint,$params){
		// setting url
		$url = self::ROOT_URL.'/'.$endpoint.'?'.((!empty($params)) ? http_build_query($params,'','&') : '');
		
		// headers
		$headers = array(
			'Accept: application/xml'
		);

		if(isset($params['format']) && $params['format'] === 'json'){
			$headers = array(
				'Accept: application/json'

			);
		}

		// curl extension
        try {
                $ch = curl_init(); // initializing curl

                curl_setopt($ch, CURLOPT_URL, $url); //url set

                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // setting headers
                curl_setopt($ch, CURLOPT_HEADER, 1); // with header true
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Request return true
                $response = curl_exec($ch);
                $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                $header = substr($response, 0,$header_size);
                $result = substr($response, $header_size);
                curl_close($ch);	// closing curl


        }

		catch (Throwable $exception){
		    echo "Error Processing Request on ".$endpoint." ".$exception->getMessage();
        }

        if(isset($params['format']) && $params['format'] === 'json'){
            $result = json_decode($result); // json decoding
        }

        else{
            // getting xml data to json and then to an array
            $result = json_decode(json_encode((array)simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA)),1);
        }

        if(!empty($result)) return $result;
	
	}
}

?>