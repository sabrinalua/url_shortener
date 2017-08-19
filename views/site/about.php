<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

//$this->title = 'About';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
            <div class="col-lg-4">
                <p>
			    	<h5 class="lead">APIs (click to download example)</h5>
			    	<li><b>GET api/list/key?key={{key}}</b>GET stats of key 
				    	<ul>eg: 
                            <?='<a href="'.Url::toRoute(['site/download', 'type'=>'all']).'">http://127.0.0.1/excercise1/web/index.php/api/list/key?key=f68d5e41</a>';
                            ?>
				    	</ul>
			    	</li>
                    <li><b>GET api/list/all</b>Get all keys and corresponding urls
                    <ul>eg: 
                        <?='<a href="'.Url::toRoute(['site/download', 'type'=>'key']).'">http://127.0.0.1/excercise1/web/index.php/api/list/all</a></ul>'
                        ;?>
                    </li>
			    </p>
            </div>
            
    </div>


    <div class="row">
            <div class="col-lg-4">
                <p>
			        <h5 class="lead">The following libs/tools were used in this project:</h5>
			        <li><a href="https://github.com/jenssegers/agent">jenseggers' ua parser</a> - parse UA details</li>
			        <li><a href="http://ipinfo.io/">ipinfo.io</a> - get info based on ip address</li>
			    </p>

            </div>
            
    </div>
    
</div>

