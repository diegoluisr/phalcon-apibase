<?php
namespace Api\Modules\Account\Controllers;

use \DateTime,
    \DateInterval,
    \Api\Controllers\AppController,
    \Api\Modules\Account\Models\User,
    \Api\Modules\Account\Models\AccessToken,
    \Phalcon\Filter,
    \Phalcon\Http\Response;

class AccountController extends AppController{

    protected $name = 'Account';

    public function __construct(){
        parent::__construct();
    }

    public function login(){
        $filter = new Filter();

        $payload = $this->request->getJsonRawBody();
        $username = $filter->sanitize($payload->username, "email");
        $password = $filter->sanitize($payload->password, "string");

        $user = User::findFirst(
            array(
                "username='$username'",
                "deleted_at='0000-00-00 00:00:00'"
            )
        );

        if(!empty($user)){
            if($this->security->checkHash($password, $user->password)){

                $time = new DateTime(date('Y-m-d H:i:s'));
                $time->add(new DateInterval('PT10M'));
                $stamp = $time->format('Y-m-d H:i:s');

                $accessToken = new AccessToken();
                $accessToken->user_agent = $this->request->getUserAgent();
                $accessToken->ip = $this->request->getClientAddress();
                $accessToken->expires_at = $stamp;
                $accessToken->user_id = $user->id;
                $accessToken->token = bin2hex(openssl_random_pseudo_bytes(16));

                if ($accessToken->save() == false) {
                    $this->output['message'] = 'error';
                    
                    foreach ($accessToken->getMessages() as $message) {
                        $messages[] = $this->translate->_($message->getMessage(), array('field' => $message->getField()));
                    }
                    $this->output['message'] = $messages;
                } else {
                    $this->output['message'] = 'Welcome!';
                    $this->output['data'] = $accessToken->token;
                }
            } else {
                $this->output['message'] = 'Password not match!';
            }
        } else {
            $this->output['message'] = 'User not found!';
        }

        $this->response->setContent(json_encode($this->output));

        return $this->response;
    }

    public function me(){
        $this->output['data'] = $this->user;
        $this->response->setContent(json_encode($this->output));
        return $this->response;
    }
    
    public function logout(){

        $access_token = $this->request->getHeader('TOKEN');

        $token = AccessToken::findFirst(
            array(
                "token='$access_token'"
            )
        );

        if(!empty($token)){
            if ($token->delete() == false) {
                $this->output['message'] = "Sorry, we can't delete the token right now";
            } else {
                $this->output['message'] = "The token was deleted successfully!";
            }
        }

        $this->response->setContent(json_encode($this->output));
        return $this->response;
    }
}