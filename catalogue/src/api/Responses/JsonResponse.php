<?php
namespace api\Responses;

use Psr\Http\Message\ResponseInterface;

class JsonResponse
{
    public static function make(ResponseInterface $response, $data ,$code = 200, $local = "fr-FR"){
       
    	if(is_object($data)){
	    	if(is_a($data,"Illuminate\Database\Eloquent\Collection")){
	    		$type = "collection";
	    		$resp = [
	    			"type" => $type,
	    			"count" => $data->count(),
	    			"locale" => $local
	    		];
    			$name = (new \ReflectionClass(get_class($data->first())))->getShortName();
    			// var_dump($name); die();
	    	}
	    	else{
	    		$type = "ressource";
	    		$resp = [
	    			"type" => $type,
	    			"locale" => $local
	    		];
	    		$name = (new \ReflectionClass(get_class($data)))->getShortName();

	    	}

	    	// var_dump(get_class($data)); die();

    		$data = $data->toArray();
	    	$data = array_merge($resp, array($name => $data));
    	}

    	$data = (is_array($data) ? json_encode($data) : (is_string($data) && is_array(json_decode($data)) ? $data : json_encode(['Something went wrong'])));
        
        return $response->withStatus($code)
            ->withHeader('Content-Type', 'application/json')
            ->write($data);
    }
}