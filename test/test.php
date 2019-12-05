<?php
/**
 * |-----------------------------------------------------------------------------------
 * @Copyright (c) 2014-2018, http://www.sizhijie.com. All Rights Reserved.
 * @Website: www.sizhijie.com
 * @Version: 思智捷管理系统 1.5.0
 * @Author : como 
 * 版权申明：szjshop网上管理系统不是一个自由软件，是思智捷科技官方推出的商业源码，严禁在未经许可的情况下
 * 拷贝、复制、传播、使用szjshop网店管理系统的任意代码，如有违反，请立即删除，否则您将面临承担相应
 * 法律责任的风险。如果需要取得官方授权，请联系官方http://www.sizhijie.com
 * |-----------------------------------------------------------------------------------
 */
require '../vendor/autoload.php';
use szjcomo\bdmap\BdMap;
$ak = 'xxx';

//地址转经纬度
/*$data = BdMap::toLatlng('永和市场','河源市',$ak);
print_r($data);
*/

$data = file_get_contents('points1.json');
$options = json_decode($data,true);


$point = ['lat'=>23.758382,'lng'=>114.703666];



$result = BdMap::searchPoint($point,$options);
var_dump($result);




//经纬度转地址
/*$result = \szjcomo\bdmap\BdMap::toAddress($data['data']['location']['lat'],$data['data']['location']['lng'],$ak);
print_r($result);*/

//计算距离
/*$result = BdMap::distance('23.766355,114.697415','31.212721803292,104.96967318351',$ak);
print_r($result);*/

//获取实时路况
/*$result = BdMap::traffic('东二环','北京市',$ak);
print_r($result);*/
