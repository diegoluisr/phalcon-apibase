<?php
namespace Api\Controllers;

use \Api\Modules\Account\Models\AccessToken,
    \Api\Modules\Account\Models\User,
    \Api\Modules\Account\Models\Group,
    \Phalcon\DI,
    \Phalcon\DI\Injectable,
    \Phalcon\Acl\Adapter\Memory as Acl,
    \Phalcon\Acl\Role,
    \Phalcon\Acl\Resource;

class AppController extends Injectable{

    protected $limit = 20;
    protected $page = 1;

    protected $output = array();
    protected $name = '';


    protected $user = null;

    public function __construct(){
        $di = DI::getDefault();
        $this->setDI($di);

        $access_token = $this->request->getHeader('TOKEN');
        $user_agent = $this->request->getUserAgent();
        $ip = $this->request->getClientAddress();

        $this->user = AccessToken::getUser($access_token, $user_agent, $ip);
    }

    protected function prepareSearch($fields){
        $request = $this->getDI()->get('request');
        $query = ($request->get('q', null, null)) ?: '';

        $prepared = array();
        $conditions = '';
        $parameters = array();
        if($query != '' && !empty($fields)) {
            $words = array_unique(explode(' ', $query));
            foreach ($words as $key => $word) {
                if(strlen($word) >= 3) {
                    foreach ($fields as $field) {
                        if($conditions != '') {
                            $conditions .= ' OR ';
                        }
                        $conditions .= $field. " LIKE :word" . $key . ":";
                    }
                }
                $parameters['word'.$key] = '%'.$word.'%';   
            }
            if($conditions != '' && count($parameters) > 0) {
                $prepared['conditions'] = $conditions;
                $prepared['bind'] = $parameters;
            }
        }
        return $prepared;
    }
}