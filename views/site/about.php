<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

//$this->title = 'About';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    	<h5 class="lead">APIs</h5>
    	<li><b>GET</b> api/list?url={{url}}</li>
    </p>
    <p>
        <h5 class="lead">The following libs/tools were used in this project:</h5>

        <li><a href="https://github.com/jenssegers/agent">jenseggers' ua parser</a> - parse UA details</li>
        <li><a href="http://ipinfo.io/">ipinfo.io</a> - get info based on ip address</li>
    </p>

    
    
</div>

