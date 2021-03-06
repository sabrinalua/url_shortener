<?php

namespace app\module\shortener\controllers;

use yii\rest\Controller;
use Yii;
use app\models\Access;
use app\models\Shortener;
use yii\helpers\Json;
use app\module\shortener\models\Util;

/**
 * Default controller for the `shortener` module
 */
class DefaultController extends Controller
{

	public function beforeAction($action){
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        \yii\helpers\Url::base();
		return true;
	}

    private function browserAccess($key_id){
        $browser = Access::findBySql("SELECT distinct browser from access where key_id= :id", [':id'=>$key_id])->all();
        $array =[];
        $i=0;
        foreach($browser as $b){
            $count = Access::findBySql("SELECT * from access where browser = :b and key_id= :id", [':id'=>$key_id, ":b"=>$b->browser])->count();
            $array[$i]['browser']=$b->browser;
            $array[$i]['count']=$count;
            $i++;
        }
        return $array;        
    }

    private function osAccess($key_id){
        $browser = Access::findBySql("SELECT distinct os from access where key_id= :id", [':id'=>$key_id])->all();
        $array =[];
        $i=0;
        foreach($browser as $b){
            $count = Access::findBySql("SELECT * from access where os = :b and key_id= :id", [':id'=>$key_id, ":b"=>$b->os])->count();
            $array[$i]['os']=$b->os;
            $array[$i]['count']=$count;
            $i++;
        }
        return $array;        
    }

    public function actionAdd(){
        $success = true;
        $post = Yii::$app->request->post();

        $long_url = $post['to_shorten'];
        $key = Util::short($post['to_shorten']);
        $model = new Shortener();
        $model->url = $long_url;
        $model->key = $key;

        if($model->save(false)){
            $homeurl = \yii\helpers\Url::home();
            $servername = Yii::$app->getRequest()->serverName;
            return ['ori_url'=>$model->url, 'short'=>$servername.$homeurl.'/'.$model->key];
        }else{
            throw new \yii\web\HttpException(400, "Unable to shorten url; please try again later",3838);
        }

    }
    public function actionIndex()
    {	
    	// $arr = ['one'=>1, 'two'=>2];
    	
        return ['homeurl'=>'localhost'.Yii::$app->homeUrl];
    }

    public function actionList(){
        $get = Yii::$app->request->get();
        $type = $get['type'];

        switch ($type) {
            case 'key':
                if(isset($get['key'])){
                    $key = $get['key'];
                    $short = Shortener::findOne(['key'=>$key]);
                    if(sizeof($short)==1){
                        $count = Access::find()->where(['key_id'=>$short->id])->count();
                        $access = Yii::$app->db->createCommand("SELECT date(access_date) as date, count(access_date) as hits FROM `access` where key_id= :id and date(access_date) BETWEEN NOW() - INTERVAL 7 DAY AND NOW() group by date(access_date)")
                        ->bindValues([':id'=>$short->id])
                        ->queryAll();

                        $frequency = Yii::$app->db->createCommand("select date, hits from (SELECT date(access_date) as date, count(access_date) as hits FROM `access` where key_id= :id group by date(access_date) order by hits desc)a limit 1")
                        ->bindValues([':id'=>$short->id])
                        ->queryAll();

                        $most_frequent_hits='';
                        if(sizeof($frequency)==1){
                            $most_frequent_hits=$frequency[0]['date'].' ('.$frequency[0]['hits'].')';
                        }

                        $bhits = self::browserAccess($short->id);
                        $oshits = self::osAccess($short->id);
                        $summary = ['key'=>$key,'total_hits'=>$count, 'hits_in_past_week'=>$access, 'most_frequent_hits'=>$most_frequent_hits, 'browser_access'=>$bhits, 'os_access'=>$oshits];
                        return $summary;
                    }else{
                        throw new \yii\web\HttpException(400, "This shortened url doesn't exist",3838);
                    }
                }else{ 
                    throw new \yii\web\HttpException(400, "Please provide a key",3838);
                }
                break;
            case 'all':
                $short = Shortener::find()->select("url, key")->all();
                return ['keys'=>$short];
                break;
            default:
                # code...
                break;
        }

    }
}
