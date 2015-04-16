<?php
namespace Api\Controllers;

use \DateTime,
    \DateInterval,
    \Api\Controllers\AppController,
    \Api\Modules\Account\Models\User,
    \Api\Modules\Account\Models\AccessToken,
    \Phalcon\Http\Response;

class IndexController extends AppController{

    protected $name = 'Index';

    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $this->output['message'] = 'API REST';
        $this->response->setStatusCode(200, "Ok")
            ->setContent(json_encode($this->output));
        return $this->response;
    }
}