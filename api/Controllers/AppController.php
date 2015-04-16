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

/**
 * /Api/Controllers/AppController.php
 * 
 * AppController
 * 
 * PHP version 5.3
 * 
 * @category   Module
 * @package    /Api/Controllers
 * @author     Diego Luis Restrepo  <diegoluisr@gmail.com>
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    GIT: $Id$
 * @since      0.0.1
*/
class AppController extends Injectable{

    protected $limit = 20;
    protected $page = 1;

    protected $output = array();
    protected $name = '';

    protected $user = null;
    
    /**
     * 
     * Constructor method
     *
     * @param string header - AccessToken token
     * @return void
    */
    public function __construct(){
        $di = DI::getDefault();
        $this->setDI($di);

        $access_token = $this->request->getHeader('TOKEN');
        $user_agent = $this->request->getUserAgent();
        $ip = $this->request->getClientAddress();

        $this->user = AccessToken::getUser($access_token, $user_agent, $ip);
    }

    /**
     * 
     * Prepare helper to search inside defined fields
     * @param array $fields
     * @return array $prepared
    */
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