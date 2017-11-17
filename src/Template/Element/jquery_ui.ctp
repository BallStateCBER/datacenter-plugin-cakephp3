<?php
$onLocalhost = stripos(env('HTTP_HOST'), 'localhost') !== false;
$domain = $onLocalhost ? '' : 'http://cberdata.org';
$pluginPath = $domain . '/data_center';

echo $this->Html->css($pluginPath . '/jquery-ui-1.11.3.custom/jquery-ui.min.css');
echo $this->Html->script($pluginPath . '/jquery-ui-1.11.3.custom/jquery-ui.min.js');
