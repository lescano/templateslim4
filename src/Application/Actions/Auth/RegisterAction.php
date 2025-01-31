<?php

//namespace App\Controllers\Users;
namespace App\Application\Actions\Auth;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Actions\Action;
use Slim\Exception\HttpBadRequestException;
use App\Infrastructure\Database\User\Users;



class RegisterAction extends Action
{

    public function __construct()
    {
        
    }

    protected function action(): Response
    {

        $data = $this->request->getParsedBody();
        $name = $data['name'];
        $lastname = $data['lastname'];

        //$username = $this->generateRandomString();
        $email = filter_var($data['mail'], FILTER_VALIDATE_EMAIL);

        if (!$name || !$lastname || !$email ) {
            throw new HttpBadRequestException($this->request, 'The data is incorrect. Please check and try again.');
        }

        $userRepo = new Users();
        $userId = $userRepo->create($data);
        
        if($userId){
            return $this->response->withHeader('Location', '/users/'.$userId)->withStatus(302);
        }else{
            return $this->response->withHeader('Location', '/')->withStatus(302);
        }
    }

    function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
}
