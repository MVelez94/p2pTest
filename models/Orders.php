<?php

namespace app\models;

use yii\db\ActiveRecord, yii\db\Expression;
use Yii;

class Orders extends ActiveRecord{
    public $STATUS = ["CREATED", "PAYED", "REJECTED"];
    const ID = "id";
    const CUSTOMER_NAME = "customer_name";
    const CUSTOMER_EMAIL = "customer_email";
    const CUSTOMER_MOBILE = "customer_mobile";
    const STATUS = "status";
    const CREATED = "CREATED";
    const PAID = "PAID";
    const REJECTED = "REJECTED";
    const EXPIRATION_HOURS = 2;
    public function beforeSave($i){
        if($i){
            $this->created_at = new Expression("NOW()");
        }
        $this->updated_at = new Expression("NOW()");
        return parent::beforeSave($i);
    }
    public function attributeLabels(){
        return [static::ID => "", static::CUSTOMER_NAME  => "Nombre del adquiriente", static::CUSTOMER_EMAIL => "Correo electrónico", static::CUSTOMER_MOBILE => "Teléfono"];
    }
    
   
    private function getAuthObject($login, $tKey){

        $t = date('c');
        $nonce = random_int(0, PHP_INT_MAX);
        $auth = new \stdClass;
        $auth->login = $login;
        $auth->tranKey = base64_encode(sha1($nonce.$t.$tKey, true));
        $auth->nonce = base64_encode($nonce);
        $auth->seed = $t;
        return $auth;

    }

    private function getSessionObject(){
        $o = new \stdClass;
        $o->locale = "en_CO";
        $o->buyer = (object)["name" => $this->customer_name, "email" => $this->customer_email, "mobile" => $this->customer_mobile];
        $o->payment = (object)["reference" => $this->id, "description" => "Pago desde WGiraldoP2Pay", "amount" => ["currency" => "COP", "total" => 1], "allowPartial" => "false"];
        $o->expiration = date('c', time()+static::EXPIRATION_HOURS*3600);
        $o->returnUrl = "http://localhost/";
        $o->ipAddress = $_SERVER["REMOTE_ADDR"];
        $o->userAgent = $_SERVER["HTTP_USER_AGENT"];
        return $o;
    }

    public function pay(){
        $o = $this->getSessionObject();
        $o->auth = $this->getAuthObject(Yii::$app->params["P2PLogin"], Yii::$app->params["P2PTranKey"]);

        $s = $this->HTTPRequest("https://test.placetopay.com/redirection/api/session/", $o);
        
        if($s){
            $r = json_decode($s);
            $this->reqId = $r->requestId;
            $this->save();
            return $r;
        }
        return null;
    }

    public function checkStatus(){
        if(!$this->reqId) return "NOT ASSIGNED";
        $o = new \stdClass;
        $o->auth = $this->getAuthObject(Yii::$app->params["P2PLogin"], Yii::$app->params["P2PTranKey"]);

        $s = $this->HTTPRequest("https://test.placetopay.com/redirection/api/session/$this->reqId", $o);
        if($s){
            return json_decode($s)->status->status;
        }
        return "NOT ASSIGNED";

    }

    private function HTTPRequest($url, $object){
        $context = stream_context_create([
            "http" => [
                "method" => "POST",
                "header" => "Content-type: application/json",
                "content" => json_encode($object)
            ]
        ]);
        $s = file_get_contents($url, false, $context);
        return $s;
    }



}
