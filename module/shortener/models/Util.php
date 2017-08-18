<?php

namespace app\module\shortener\models;

use yii\rest\Controller;
use Yii;
use app\models\Access;
use app\models\Shortener;
use yii\helpers\Json;


class Util {

	public function short($url){
        $date = (new \DateTime())->getTimestamp();
        $hasher = ''.rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
        $pk = hash_hmac('md5', $date, $hasher);
        // return hash('adler32', hash('crc32', $url.$pk));
        return hash('crc32', $url.$pk);
    }
}
?>