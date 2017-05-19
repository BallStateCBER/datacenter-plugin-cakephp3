<?php
$on_localhost = stripos(env('HTTP_HOST'), 'localhost') !== false;
$domain = $on_localhost ? '' : 'http://cberdata.org';
$plugin_path = $domain.'/data_center';

echo $this->Html->css($plugin_path.'/jquery-ui-1.11.3.custom/jquery-ui.min.css');
echo $this->Html->script($plugin_path.'/jquery-ui-1.11.3.custom/jquery-ui.min.js', ['block' => 'scriptBottom']);