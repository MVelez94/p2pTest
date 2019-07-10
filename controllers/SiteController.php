<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Orders;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $q = Orders::find();
        foreach(Yii::$app->request->get() as $n => $v){
            if(is_string($v) && $v != "r" && $v != ""){
                $q->where([$n => $v]);
            }
        }
        $orderList = $q->all();
        return $this->render('index', ["orders" => $orderList]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionNewOrder()
    {
       
        return $this->render('neworder', [
            'model' => new Orders,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionNewOrderProcess()
    {
        
        $req = Yii::$app->request->post();
        $fN = "Orders";
        if(isset($req[$fN][Orders::ID])){
            $id = intval($req[$fN][Orders::ID]);
            $o = new Orders;
            
            if($id > 0){
                $o = Orders::find()->where([Orders::ID => $id])->one();
            }
            
            $o->setAttributes($req[$fN], false);
            $o->status = Orders::CREATED;
            if($o->save()){
                header("Location: ?r=site/order-info&id=$o->id");
                die;
            }
            return $this->render('neworder', ["model" => $o]);
        }
        return $this->action403(); // TODO: Make a view with a 403
        
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionOrderInfo($id = null)
    {
        if(!$id){
            $req = Yii::$app->request->get();
            if(isset($req[Orders::ID])) $id = intval($req[Orders::ID]);
        }
        
        if($id){
            $o = Orders::find()->where([Orders::ID => $id])->one();
            
            if($o){
                $status = $o->checkStatus();
                if($o->status == Orders::CREATED){
                    if($status == "APPROVED"){
                        $o->status = Orders::PAID;
                        $o->save();
                    } else if($status == "REJECTED"){
                        $o->status = Orders::REJECTED;
                        $o->save();
                    }
                    return $this->actionOrderInfo($o->id);
                }
                
                return $this->render('orderinfo', ["model" => $o, "p2pStatus" => $status]);
            }
        }

        return $this->action403();        
        
        
    }

    public function actionOrderPay(){
        $id = 0;
        $req = Yii::$app->request->get();
        if(isset($req[Orders::ID])) $id = intval($req[Orders::ID]);
        if($id){
            $o = Orders::find()->where([Orders::ID => $id])->one();
            $r = $o->pay();
            if($r){
                if($r->status->status == "OK")
                {
                    $o->reqId = $r->requestId;
                    header("Location: $r->processUrl");
                    die;
                }
            }
            
                $this->action500();
            
            
        }
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function action500(){
        echo "Internal server error";
    }
    public function action403()
    {
        echo "Forbidden";
    }
}
