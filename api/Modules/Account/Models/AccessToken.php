<?php
namespace Api\Modules\Account\Models;

use Api\Models\AppModel,
    Api\Modules\Account\Models\User,
    DateTime,
    DateInterval;
/**
 * /Api/Modules/Account/Models/AccessToken.php
 * 
 * AccessToken
 * 
 * PHP version 5.3
 * 
 * @category   Module
 * @package    /Api/Modules/Account/Models
 * @author     Diego Luis Restrepo  <diegoluisr@gmail.com>
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    GIT: $Id$
 * @since      0.0.1
*/
class AccessToken extends AppModel {

    /**
     * @Primary
     * @Identity
     * @Column(type="integer", nullable=false)
     */
    public $id = 0;

    /**
     * @Related(type="belongsTo", model="Api\Modules\Account\Models\User")
     * @Column(type="integer", nullable=false)
     */
    public $user_id = 0;

    /**
     * @Unique
     * @Column(type="string", nullable=false)
     */
    public $token = '';

    /**
     * @Column(type="string", nullable=false)
     */
    public $ip = '';

    /**
     * @Column(type="string", nullable=false, max=255)
     */
    public $user_agent = '';

    /**
     * @Column(type="datetime", nullable=false)
     */
    public $expires_at = '0000-00-00 00:00:00';

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
        return "access_tokens";
    }
    /**
     * 
     * getUser
     *
     * @return null
    */
    public static function getUser($token, $user_agent, $ip) {
        $token = self::query()
            ->where('token = :token:')
            ->andWhere('user_agent = :user_agent:')
            ->andWhere('expires_at > :expires_at:')
            ->andWhere('ip = :ip:')
            ->bind(
                array(
                    'token' => $token,
                    'user_agent' => $user_agent,
                    'ip' => $ip,
                    'expires_at' => date('Y-m-d H:i:s')
                ))
            ->order('id')
            ->limit(1)
            ->execute(); 

        if(isset($token[0])){
            $token = $token[0];

            $time = new DateTime(date('Y-m-d H:i:s'));
            $time->add(new DateInterval('PT10M'));
            $stamp = $time->format('Y-m-d H:i:s');
            
            $token->expires = $stamp;
            if($token->update() == false){
                // 
            }
        } else {
            $token = null;
        }


        if($token != null) {
            return User::getValidUser($token->user_id);
        }
        return null;
    }

}