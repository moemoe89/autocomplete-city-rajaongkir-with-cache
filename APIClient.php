<?php namespace Momo\RajaOngkirClient;

class APIClient {

    private $key;

    public function __construct($key) {
        $this->key = $key;
    }

    public function getCost($origin,$destination,$weight,$courirer = null){

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://rajaongkir.com/api/starter/cost",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "origin=$origin&destination=$destination&weight=$weight&courier=$courirer",
		  CURLOPT_HTTPHEADER => array(
		    "content-type: application/x-www-form-urlencoded",
		    "key: $this->key"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  return "cURL Error #:" . $err;
		} else {
		  $cost = json_decode($response);
		  $cost = $cost->rajaongkir->results;
		  return json_encode($cost);
		}

    }

    public function getCity($term){

    	$url = "http://rajaongkir.com/api/starter/city";
    	$cacheFile = 'cache' . DIRECTORY_SEPARATOR . md5($url);

	    if (file_exists($cacheFile)) {
	        $fh = fopen($cacheFile, 'r');
	        $cacheTime = trim(fgets($fh));

	        if ($cacheTime > strtotime('-60 minutes')) {
	        	$createCache = false;
	            $json = str_replace($cacheTime,'',fread($fh,filesize($cacheFile)));
	            fclose($fh);
	        } else {
	        	$createCache = true;
	        }

	    } else {
	    	$createCache = true;
	    }
			
		if($createCache == true){

	        $curl = curl_init();

	        curl_setopt_array($curl, array(
	          CURLOPT_URL => $url,
	          CURLOPT_RETURNTRANSFER => true,
	          CURLOPT_ENCODING => "",
	          CURLOPT_MAXREDIRS => 10,
	          CURLOPT_TIMEOUT => 30,
	          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	          CURLOPT_CUSTOMREQUEST => "GET",
	          CURLOPT_HTTPHEADER => array(
	            "key: $this->key"
	          ),
	        ));

	        $response = curl_exec($curl);
	        $err = curl_error($curl);

	        curl_close($curl);

	        if ($err) {
	          return $err;
	          exit;
	        } else {

	          $fh = fopen($cacheFile, 'w');
	          fwrite($fh, time() . "\n");
	          fwrite($fh, $response);
	          fclose($fh);

	          $json = $response;

	        }

		}

		$decodeJson = json_decode($json);
		$city = $decodeJson->rajaongkir->results;

		$input = @$term;
		$result = array_filter($city, function($c) use ($input) {
		    if (stripos($c->city_name, $input) !== false) {
		        return true;
		    }
		    return false;
		});

		return json_encode($result);

    }

    public function getProvince($term){

    	$url = "http://rajaongkir.com/api/starter/province";
    	$cacheFile = 'cache' . DIRECTORY_SEPARATOR . md5($url);

	    if (file_exists($cacheFile)) {
	        $fh = fopen($cacheFile, 'r');
	        $cacheTime = trim(fgets($fh));

	        if ($cacheTime > strtotime('-60 minutes')) {
	        	$createCache = false;
	            $json = str_replace($cacheTime,'',fread($fh,filesize($cacheFile)));
	            fclose($fh);
	        } else {
	        	$createCache = true;
	        }

	    } else {
	    	$createCache = true;
	    }
			
		if($createCache == true){

	        $curl = curl_init();

	        curl_setopt_array($curl, array(
	          CURLOPT_URL => $url,
	          CURLOPT_RETURNTRANSFER => true,
	          CURLOPT_ENCODING => "",
	          CURLOPT_MAXREDIRS => 10,
	          CURLOPT_TIMEOUT => 30,
	          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	          CURLOPT_CUSTOMREQUEST => "GET",
	          CURLOPT_HTTPHEADER => array(
	            "key: $this->key"
	          ),
	        ));

	        $response = curl_exec($curl);
	        $err = curl_error($curl);

	        curl_close($curl);

	        if ($err) {
	          return $err;
	          exit;
	        } else {

	          $fh = fopen($cacheFile, 'w');
	          fwrite($fh, time() . "\n");
	          fwrite($fh, $response);
	          fclose($fh);

	          $json = $response;

	        }

		}

		$decodeJson = json_decode($json);
		$province = $decodeJson->rajaongkir->results;

		$input = @$term;
		$result = array_filter($province, function($c) use ($input) {
		    if (stripos($c->province, $input) !== false) {
		        return true;
		    }
		    return false;
		});

		return json_encode($result);

    }

}

?>