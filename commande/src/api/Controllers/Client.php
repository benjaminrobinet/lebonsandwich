<?php
	namespace api\Controllers;

	use api\Errors\JsonError;
	use api\Errors\JsonNotFound;
	use api\Models\Commande;
	use api\Models\Item;
	use api\Models\Clients as Clients;
	use api\Responses\CollectionResponse;
	use api\Responses\ResourceResponse;
	use api\Responses\JsonResponse;
	use DateTime;
	use GuzzleHttp\Psr7\Response;
	use Psr\Container\ContainerInterface;
	use Psr\Http\Message\RequestInterface;
	use Psr\Http\Message\ResponseInterface;
	use Ramsey\Uuid\Uuid;

	class Client{
	    protected $container;

	    public function __construct(ContainerInterface $container) {
	        $this->container = $container;
	    }

	    public function auth(RequestInterface $request, ResponseInterface $response, $args){
	        $id = $args['id'];

	        var_dump($id); die();
	    }

     	public function create(RequestInterface $request, ResponseInterface $response, $args){
	        //Récupération du body
	        $body = $request->getParsedBody();

	        //Création d'un nouveau client
	        if(!empty($body["fullname"] && is_string($body["fullname"]))){
	        	$id_client = Uuid::uuid4();

	        	$new_client = new Clients;
	        	$new_client->id = $id_client;
	        	$new_client->fullname = $body["fullname"];
	        	$new_client->save();

	        	$client = [
	        		"id"=>$id_client,
	        		"fullname"=> $body["fullname"]
	        	];

	        	$res = JsonResponse::make($response, ['client' => $client]);
        		return $res;
	        }else{
	        	$jsonError = new JsonError();
            	$jsonError->make($response, 'Bad Request', 400);
	        }
	    }
	}
?>