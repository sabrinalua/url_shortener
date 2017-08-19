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

    public function actionDownload(){
        $get = Yii::$app->request->get();
        $type = $get['type'];

        switch ($type) {
            case 'all':
                Yii::$app->response->sendFile(__DIR__.'/allkeys.json', "all_example.json");
                break;
            case 'key':
                Yii::$app->response->sendFile(__DIR__.'/stat_by_key.json', "stat_by_key.json");
                break;
        }
    }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
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
        $u= "unknown";

        $shortener = Shortener::findOne(['key'=>$get['text']]);
        if(sizeof($shortener)==1){
            $ua = Yii::$app->request->getUserAgent();
            try{
                $details = json_decode(file_get_contents("http://ipinfo.io/"));
                $access = new Access();
                $ua_details =self::UaParser($ua); 
                $access->key_id =$shortener->id;
                $access->ip_address = strlen($details->ip) > 0 ? $details->ip: $u;
                $access->city= strlen($details->city) >0 ? $details->city: $u;
                $access->region= strlen($details->region)>0 ? $details->region : $u;
                $access->country= strlen($details->country)>0? $details->country: $u;
                $access->geolocation= strlen($details->loc)>0? $details->loc: $u;
                $access->os= $ua_details['os'];
                $access->browser= $ua_details['browser'];
                $access->browser_version= $ua_details['browser_version'];
                $access->device_type= $ua_details['type'];
                $access->save(false);
                return $this->redirect($shortener->url, 302);
            }catch(\Exception $e){
                $accessx = new Access();
                $ua_details =self::UaParser($ua); 
                $accessx->key_id =$shortener->id;
                $accessx->ip_address = $u;
                $accessx->city= $u;
                $accessx->region= $u;
                $accessx->country= $u;
                $accessx->geolocation= $u;
                $accessx->os= $ua_details['os'];
                $accessx->browser= $ua_details['browser'];
                $accessx->browser_version= $ua_details['browser_version'];
                $accessx->device_type= $ua_details['type'];
                $accessx->save(false);
                return $this->redirect($shortener->url, 302);
                // return $this->render('error', ['message'=>"the server isn't online; unable to capture ipinfo", "name"=>"Error"]);
            }
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

    public function actionIndex(){
       $homeurl = \yii\helpers\Url::home();
        $servername = Yii::$app->getRequest()->serverName;

         $shortener = Yii::$app->db->createCommand("select shortener.id, shortener.url, shortener.key from shortener ")
                ->queryAll();
        $i=0;
        foreach($shortener as $s){
            $hit = Access::find()->where(['key_id'=>$s['id']])->count();
            $shortener[$i]['hits']= $hit;
            unset($shortener[$i]['id']);
            $i++;
        }
        return $this->render('short2', ['home'=>$servername.$homeurl, 'urls'=>$shortener]);
    }

}
