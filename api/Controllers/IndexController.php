<?php
namespace Api\Controllers;

use \DateTime,
    \DateInterval,
    \Api\Controllers\AppController,
    \Api\Modules\Account\Models\User,
    \Api\Modules\Account\Models\AccessToken,
    \Phalcon\Http\Response;

/**
 * /Api/Controllers/IndexController.php
 * 
 * IndexController
 * 
 * PHP version 5.3
 * 
 * @category   Module
 * @package    /Api/Controllers
 * @author     Diego Luis Restrepo <diegoluisr@gmail.com>
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    GIT: $Id$
 * @since      0.0.1
 */
class IndexController extends AppController{

    protected $name = 'Index';

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
     * Index method consumed as HTTP GET method
     *
     * @return Response $this->response
    */
    public function index(){
        $this->output['message'] = 'API REST';
        $this->response->setStatusCode(200, "Ok")
            ->setContent(json_encode($this->output));
        return $this->response;
    }
}