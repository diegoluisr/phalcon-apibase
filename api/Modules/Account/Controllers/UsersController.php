<?php
namespace Api\Modules\Account\Controllers;

use \Api\Controllers\AppController,
    \Api\Modules\Account\Models\User,
    \Phalcon\Filter,
    \Phalcon\Http\Response;

/**
 * /Api/Modules/Account/Controllers/UsersController.php
 * 
 * UsersController
 * 
 * PHP version 5.3
 * 
 * @category   Module
 * @package    /Api/Modules/Account/Controllers
 * @author     Diego Luis Restrepo <diegoluisr@gmail.com>
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    GIT: $Id$
 * @since      0.0.1
*/
class UsersController extends AppController{

    protected $name = 'Users';

    /**
     * 
     * Constructor method
     *
     * @return void
    */
    public function __construct(){
        parent::__construct();
    }
    
    /**
     * 
     * Search an Account
     *
     * @return Response $this->response
    */
    public function index(){

        $options = $this->prepareSearch(
            array(
                'Api\Modules\Account\Models\User.username'
            )
        );

        $count = User::count($options);

        $total = ceil($count / $this->limit);

        $offset = $this->limit * ($this->page - 1);

        $options['limit'] = $this->limit;
        $options['offset'] = $offset;
        
        $users = User::find($options);

        $data = array();
        foreach ($users as $user) {
            $data[] = array(
                'id' => $user->id,
                'username' => $user->username,
                'created_at' => $user->created_at
            );
        }

        $this->output['data'] = $data;

        $this->output['pagination'] = array(
            'limit' => $this->limit,
            'page' => $this->page,
            'total' => $total,
        );

        $this->response->setContent(json_encode($this->output));
        return $this->response;
    }
    /**
     * 
     * Add an Account
     *
     * @param string $_POST['email']         User email
     * @param string $_POST['password']      User password
     *
     * @return Response $this->response
    */
    public function add() {

        $filter = new Filter();

        $payload = $this->request->getJsonRawBody();

        $username = $filter->sanitize($payload->username, "email");
        $password = $filter->sanitize($payload->password, "string");

        $user = new User();
        $user->username = $username;
        $user->password = $this->security->hash($password);
        $user->group_id = DEFAULT_CREATE_USER_GROUP_ID;

        if($user->create() == false){
            $messages = array();
            foreach ($user->getMessages() as $message) {
                $messages[] = $message->getMessage();
            }
            $this->output['message'] = $messages;
        }

        $this->response->setContent(json_encode($this->output));
        return $this->response;
    }

    /**
     * 
     * Edit an Account
     *
     * @return Response $this->response
    */
    public function edit($id = null) {

        if($id != null) {
            $request = $this->getDI()->get('request');

            $user = User::findFirst(base_convert($id, 32, 10));

            $user->username = ($request->getPut('username', null, null)) ?: $user->username;
            $user->password = ($this->security->hash($request->getPut('password', null, null))) ?: $user->password;
            $user->email = ($request->getPut('email', null, null)) ?: $user->email;
            $user->group_id = ($request->getPut('group_id', null, null)) ?: $user->group_id;

            if($user->update() == false) {
                $messages = array();
                foreach ($user->getMessages() as $message) {
                    $messages[] = $this->translate->_($message->getMessage(), array('field' => $message->getField()));
                }
                $this->output['message'] = $messages;
            }
        }

        $this->response->setContent(json_encode($this->output));
        return $this->response;
    }
    /**
     * 
     * View an Account
     *
     * @return Response $this->response
    */
    public function view($id = null) {
        $this->isAuthorized('view');
        if($id != null) {
            $user = User::findFirst($id);

            $data[] = array(
                'id' => base_convert($user->id, 10, 32),
                'username' => $user->username,
            );
            $this->output['data'] = $data;
        }
        $this->response->setContent(json_encode($this->output));
        return $this->response;
    }
}