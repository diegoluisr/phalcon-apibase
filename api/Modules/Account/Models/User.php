<?php
namespace Api\Modules\Account\Models;

use Api\Models\AppModel;

class User extends AppModel {

    /**
     * @Primary
     * @Identity
     * @Column(type="integer", nullable=false)
     */
    public $id = 0;

    /**
     * @Related(type="belongsTo", model="Api\Modules\Account\Models\Group")
     * @Column(type="integer", nullable=false)
     */
    public $group_id = 0;

    /**
     * @Unique
     * @Column(type="email", nullable=false)
     */
    public $username = '';

    /**
     * @Column(type="string", nullable=false, max=100)
     */
    public $password = '';

    /**
     * @Column(type="datetime", nullable=false)
     */
    public $created_at = '0000-00-00 00:00:00';

    /**
     * @Column(type="datetime", nullable=false)
     */
    public $modified_at = '0000-00-00 00:00:00';

    /**
     * @Column(type="datetime", nullable=false)
     */
    public $deleted_at = '0000-00-00 00:00:00';


    public function initialize() {
        $this->belongsTo('group_id', '\Api\Modules\Account\Models\Group', 'id', array(
            'alias' => 'Group'
        ));
        $this->useDynamicUpdate(true);
        return;
    }

    public function getSource() {
        return "users";
    }

    public function beforeCreate() {
        $this->created_at = date('Y-m-d H:i:s');
        $this->modified_at = date('Y-m-d H:i:s');
        $this->deleted_at = '0000-00-00 00:00:00';
    }

    public function beforeUpdate() {
        $this->modified_at = date('Y-m-d H:i:s');
    }

    public function validation() {
        parent::validation();
        return $this->validationHasFailed() != true;
    }

    public static function getValidUser($id) {
        $user = self::findFirst(
            array(
                'id = :user_id: AND deleted_at = :deleted:',
                'bind' => array(
                    'user_id' => $id,
                    'deleted' => '0000-00-00 00:00:00',
                )
            )
        );

        if(is_object($user)){
            $usr = array();
            $usr['id'] = $user->id;
            $usr['username'] = $user->username;
            $usr['created_at'] = $user->created_at;
            $usr['group']['id'] = $user->group_id;
            $usr['group']['name'] = $user->Group->name;

            return $usr;
        }
        return null;
    }

}