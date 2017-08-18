<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Access;
use app\models\Shortener;
use Jenssegers\Agent\Agent;


class SiteController extends Controller
{
    /**
     * @inheritdoc
     */

    public function beforeAction($action){
        
        return true;
    }
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
     * @inheritdoc
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
        return $this->render('index');
    }

    private function UaParser($ua){
        $agent = new Agent();
        $agent->setUserAgent($ua);
        $array =[];

        $array['os'] = $agent->platform(); //os
        $array['browser'] = $agent->browser(); //browser
        $array['browser_version'] = $agent->version($array['browser']); //browser version
        if($agent->isDesktop()){
            $array['type']= 'desktop';
        }else{
            if($agent->isPhone()){
                $array['type']='phone';
            }elseif($agent->isTablet()){
                $array['type']='tablet';
            }
        }
        return $array;
    }

    public function actionRedirect(){
        $get = Yii::$app->request->get();
        
        $shortener = Shortener::findOne(['key'=>$get['text']]);
        if(sizeof($shortener)==1){
            $ua = Yii::$app->request->getUserAgent();
            $details = json_decode(file_get_contents("http://ipinfo.io/"));
            $access = new Access();
            $ua_details =self::UaParser($ua); 
            $access->key_id =$shortener->id;
            $access->ip_address = $details->ip;
            $access->city= $details->city;
            $access->region= $details->region;
            $access->country= $details->country;
            $access->geolocation= $details->loc;
            $access->os= $ua_details['os'];
            $access->browser= $ua_details['browser'];
            $access->browser_version= $ua_details['browser_version'];
            $access->device_type= $ua_details['type'];
            $access->save(false);
            return $this->redirect($shortener->url, 302);

        }else{
            return $this->render('error', ['message'=>"the shortened url doesn't exist", "name"=>"Error"]);
        }
        
    }

    private function short($url){
        $date = (new \DateTime())->getTimestamp();
        $hasher = ''.rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
        $pk = hash_hmac('md5', $date, $hasher);
        // return hash('adler32', hash('crc32', $url.$pk));
        return hash('crc32', $url.$pk);

    }

    public function actionShort(){
       $homeurl = \yii\helpers\Url::home();
        $servername = Yii::$app->getRequest()->serverName;

         $shortener = Yii::$app->db->createCommand("select shortener.id, shortener.url, shortener.key from shortener ")
                ->queryAll();
        $i=0;
        foreach($shortener as $s){
            var_dump($s['id']);
            $hit = Access::find()->where(['key_id'=>$s['id']])->count();
            $shortener[$i]['hits']= $hit;
            unset($shortener[$i]['id']);
            $i++;
        }
        return $this->render('short2', ['home'=>$servername.$homeurl, 'urls'=>$shortener]);
    }

    /*public function actionShortener2(){
        $homeurl = \yii\helpers\Url::home();
        $servername = Yii::$app->getRequest()->serverName;
        $get = Yii::$app->request->get();
        $key = $get['key'];
        return $this->render('url_shortener_rdr', ['servername'=>$servername,'homeurl'=>$homeurl, 'key'=>$key]);
    }*/

    /*
    public function actionShortener(){
        $homeurl = \yii\helpers\Url::home();
        $servername = Yii::$app->getRequest()->serverName;

        $model = new Shortener();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->key = self::short($model->url);
                $model->url = $model->url;
                // $bool = $model->save(false)? 1:0;
                $key = $model->key;
                if($model->save()){

                    Yii::$app->getSession()->setFlash('key', $model->key);
                    // return $this->redirect(['site/shortener2','key'=>$model->key]);
                }else{
                     $model->key = self::short($model->url);
                     $model->save();
                }
            }
        }
        return $this->render('url_shortener', [
            'model' => $model,
        ]);
    }
    */
}
