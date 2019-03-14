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
	use Firebase\JWT\JWT;

	class Client{
	    protected $container;

	    public function __construct(ContainerInterface $container) {
	        $this->container = $container;
	    }

	    public function auth(RequestInterface $request, ResponseInterface $response, $args){
	    	$client_log = $request->getParsedBody();

            //Si le client fourni ses identifiants
            if(!empty($client_log["username"]) && !empty($client_log["password"])){
             	//Récupération du l'utilisateur
		    	$current_user = Clients::find($args["id"]);

		    	//Si les identifiants correspondent
		    	if($client_log["username"] == $current_user["username"] && password_verify($client_log["password"], $current_user["password"])){
		    		//Clé secret 
			    	$secret = "BenjaminHugo";

			    	//Création du token
			    	$token = JWT::encode([ 	
			    		'iat' => time(), 'exp'=>time()+3600,
						'uid' => $current_user->id,
						'name'=> $current_user->fullname
					],$secret, 'HS512' );

			    	$user = [
			    		"token"=>$token
			    	];

			    	$response = JsonResponse::make($response, $user);
		    		return $response; 
		    	}else{
		    		$jsonError = new JsonError();
            		$jsonError->make($response, 'Authentication Error', 401);
		    	}
            }else{
            	$jsonError = new JsonError();
            	$jsonError->make($response, 'Unprocessable entity', 422);
            }
	    }

     	public function create(RequestInterface $request, ResponseInterface $response, $args){
	        //Récupération du body
	        $body = $request->getParsedBody();

	        //Création d'un nouveau client
	        if(!empty($body["fullname"]) && !empty($body["username"]) && !empty($body["password"]) && !empty($body["email"])){

	        	//Si le username est déjà utilisé
	        	if(!Clients::where("fullname", $body["fullname"])->first()){
	        		if(filter_var($body["email"], FILTER_VALIDATE_EMAIL)){
	        			//ID du nouveau client
			        	$id_client = Uuid::uuid4();

			        	//Création d'une nouvelle instance de "Client"
			        	$new_client = new Clients;
			        	$new_client->id = $id_client;
			        	$new_client->fullname = $body["fullname"];
			        	$new_client->username = $body["username"];
			        	$new_client->password = password_hash($body["password"], PASSWORD_DEFAULT);
			        	$new_client->email = $body["email"];
			        	$new_client->save();

			        	$client = [
			        		"id"=>$id_client,
			        		"fullname"=> $body["fullname"],
			        		"email"=>$body["email"]
			        	];

			        	$res = JsonResponse::make($response, ['client' => $client], 201);
		        		return $res;
	        		}else{
	        			$jsonError = new JsonError();
            			$jsonError->make($response, 'Bad Request: Invalid e-mail', 400);
	        		}
	        	}else{
	        		$jsonError = new JsonError();
            		$jsonError->make($response, 'Bad Request: Client already exist', 400);
	        	}
	        }else{
	        	$jsonError = new JsonError();
            	$jsonError->make($response, 'Unprocessable entity', 422);
	        }
	    }

	    public function single(RequestInterface $request, ResponseInterface $response, $args){
	    	$current_user = Clients::find($args["id"]);

    		$client = [
	    		"fullname"=>$current_user->fullname,
	    		"username"=>$current_user->username,
	    		"email"=>$current_user->email,
	    		"sold"=>($current_user->purshase_cumul)*0.05
	    	];

	    	$res = JsonResponse::make($response, ['client' => $client]);
    		return $res;
	    }

	    public function commandes(RequestInterface $request, ResponseInterface $response, $args){
    		//Récuperer les commandes d'un utilisateur
    		$commandes = Commande::where("client_id", $args["id"])->get();

    		
	    }
	}
?>