<?php
namespace Api\Modules\Account\Models;

use Api\Models\AppModel;

/**
 * /Api/Modules/Account/Models/Group.php
 * 
 * Group
 * 
 * PHP version 5.3
 * 
 * @category   Module
 * @package    /Api/Modules/Account/Models
 * @author     Diego Luis Restrepo <diegoluisr@gmail.com>
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    GIT: $Id$
 * @since      0.0.1
 */
class Group extends AppModel {

    /**
     * @Primary
     * @Identity
     * @Column(type="integer", nullable=false)
    */
    public $id = 0;

    /**
     * @Unique
     * @Column(type="string", nullable=false)
    */
    public $name = '';
    
    /**
     * 
     * initialize
     *
     * @return void
    */
    public function initialize() {
        return;
    }

    /**
     * 
     * getSource
     *
     * @return void
    */
    public function getSource() {
        return "groups";
    }

    /**
     * 
     * validation
     *
     * @return $this->validationHasFailed
    */
    public function validation() {
        parent::validation();
        return $this->validationHasFailed() != true;
    }
}