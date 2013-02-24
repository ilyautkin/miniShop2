<?php

if (empty($_REQUEST['action'])) {
	die('Access denied');
}
else {
	$action = $_REQUEST['action'];
}

define('MODX_API_MODE', true);
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/index.php';

$modx->getService('error','error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');
$modx->error->message = null;

/* @var miniShop2 $miniShop2 */
$miniShop2 = $modx->getService('minishop2','miniShop2',$modx->getOption('minishop2.core_path',null,$modx->getOption('core_path').'components/minishop2/').'model/minishop2/',array());

if ($modx->error->hasError() || !($miniShop2 instanceof miniShop2)) {die('Error');}

$ctx = !empty($_REQUEST['ctx']) ? $_REQUEST['ctx'] : 'web';
$miniShop2->initialize($ctx, array('json_response' => true));

switch ($action) {
	case 'cart/add': $response = $miniShop2->cart->add(@$_POST['id'], @$_POST['count'], @$_POST['options']); break;
	case 'cart/change': $response = $miniShop2->cart->change(@$_POST['key'], @$_POST['count']); break;
	case 'cart/remove': $response = $miniShop2->cart->remove(@$_POST['key']); break;
	case 'cart/clean': $response = $miniShop2->cart->clean(); break;
	default: $response = json_encode(array('success' => false, 'message' => $modx->lexicon('ms2_err_unknown')));
}

exit($response);