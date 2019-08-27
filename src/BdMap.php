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
namespace szjcomo\bdmap;
/**
 * 百度地图功能集合
 */
Class BdMap{
	/**
	 * 百度地图api域名
	 */
	Protected const HostURL 			= 'http://api.map.baidu.com/';
	/**
	 * 地址经纬度转
	 */
	Protected const ToLatLngURL 		= 'geocoding/v3/?';
	/**
	 * 经纬度转地址
	 */
	Protected const ToAddressURL 		= 'reverse_geocoding/v3/?';
	/**
	 * 步行批理计算距离
	 */
	Protected const DistanceWalkingURL 	= 'routematrix/v2/walking?';
	/**
	 * 驾车批理计算距离
	 */
	Protected const DistanceDrivingURL 	= 'routematrix/v2/driving?';
	/**
	 * 骑行批理计算距离
	 */
	Protected const DistanceRidingURL 	= 'routematrix/v2/riding?';
	/**
	 * 计算实时路况
	 */
	Protected const TrafficURL 			= 'traffic/v1/road?';
	/**
	 * [addrTolatlng 地址转经纬度]
	 * @Author    como
	 * @DateTime  2019-08-27
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     string     $address [description]
	 * @param     [type]     $city    [description]
	 * @param     [type]     $ak      [description]
	 * @return    [type]              [description]
	 */
	Public static function toLatlng($address = '',$city = null,$ak = null,$options = []){
		$map = array_merge(['address'=>$address,'city'=>$city,'ak'=>$ak,'output'=>'json'],$options);
		$queryParams = self::toBuildParams($map);
		try{
			$result = self::request(self::HostURL.self::ToLatLngURL.$queryParams);
			if($result['err'] == true) return $result;
			return self::appResult($result['info'],$result['data']['result'],false);
		} catch(\Exception $err){
			return self::appResult($err->getMessage());
		}
	}
	/**
	 * [Request 执行网络请求]
	 * @Author    como
	 * @DateTime  2019-08-27
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     [type]     $url  [description]
	 * @param     [type]     $data [description]
	 * @param     string     $type [description]
	 */
	Protected static function request($url,$data = [],$type = 'get'){
		try{
			switch($type){
				case 'post':
					$res = self::curl_post($url,$data);
					break;
				default:
					$res = self::curl_get($url);
			}
			$data = json_decode($res,true);
			if(!empty($data) &&  !empty($data['status'])){
				$info = empty($data['message'])?'ERROR':$data['message'];
				return self::appResult($info,$data);
			} else {
				return self::appResult('SUCCESS',$data,false);
			}
		} catch(\Exception $err){
			return self::appResult($err->getMessage());
		}
	}
	/**
	 * [toAddress 经纬度转地址]
	 * @Author    como
	 * @DateTime  2019-08-27
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     [type]     $lat     [description]
	 * @param     [type]     $lng     [description]
	 * @param     [type]     $ak      [description]
	 * @param     array      $options [description]
	 * @return    [type]              [description]
	 */
	Public static function toAddress($lat = null,$lng = null,$ak = null,$options = []){
		$map = array_merge(['location'=>$lat.','.$lng,'ak'=>$ak,'output'=>'json'],$options);
		try{
			$queryParams = self::toBuildParams($map);
			$result = self::request(self::HostURL.self::ToAddressURL.$queryParams);
			if($result['err'] == true) return $result;
			return self::appResult($result['info'],$result['data']['result'],false);
		} catch(\Exception $err){
			return self::appResult($err->getMessage());
		}
	}
	/**
	 * [curl_get 获取]
	 * @作者     como
	 * @时间     2018-07-23
	 * @版权     FASTNODEJS WEB  FRAMEWORK
	 * @版本     1.0.1
	 * @param  string     $url [description]
	 * @return [type]          [description]
	 */
	static function curl_get($url = '',$header = array()){
		$ch = curl_init();  
		curl_setopt($ch, CURLOPT_URL, $url);  
		curl_setopt($ch, CURLOPT_HEADER, 0);
		if(empty($header)){
			curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);  
		$data = curl_exec($ch);  
		if (curl_errno($ch)) {
	        return curl_error($ch);//捕抓异常
	    }
		curl_close($ch);
		return $data;
	}
	/**
	 * [curl_post 提交]
	 * @作者     como
	 * @时间     2018-07-23
	 * @版权     FASTNODEJS WEB       FRAMEWORK
	 * @版本     1.0.1
	 * @param  [type]     $url      [description]
	 * @param  array      $postdata [description]
	 * @return [type]               [description]
	 */
	static function curl_post($url,$postdata = array(),$header = array()){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		if(empty($header)){
			curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);    
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);  
		$data = curl_exec($ch);
		if (curl_errno($ch)) {
	        return curl_error($ch);//捕抓异常
	    }
		curl_close($ch);
		return $data;
	}
	/**
	 * [Distance 计算地址之间的距离]
	 * @Author    como
	 * @DateTime  2019-08-27
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     [type]     $org     [description]
	 * @param     [type]     $dest    [description]
	 * @param     [type]     $ak      [description]
	 * @param     array      $options [description]
	 */
	Public static function distance($org = null,$dest = null,$ak = null,$options = [],$type = 'driving'){
		$map = array_merge(['ak'=>$ak,'origins'=>$org,'destinations'=>$dest,'output'=>'json'],$options);
		try{
			$queryParams = self::toBuildParams($map);
			switch($type){
				case 'walking':
					$result = self::request(self::HostURL.self::DistanceWalkingURL.$queryParams);
					break;
				case 'riding':
					$result = self::request(self::HostURL.self::DistanceRidingURL.$queryParams);
					break;
				default:
					$result = self::request(self::HostURL.self::DistanceDrivingURL.$queryParams);
			}
			if($result['err'] == true) return $result;
			return self::appResult($result['info'],$result['data']['result'],false);
		} catch(\Exception $err){
			return self::appResult($err->getMessage());
		}
	}
	/**
	 * [traffic 交通路况实时查询]
	 * @Author    como
	 * @DateTime  2019-08-27
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     [type]     $road_name [description]
	 * @param     [type]     $city      [description]
	 * @param     [type]     $ak        [description]
	 * @param     array      $options   [description]
	 * @return    [type]                [description]
	 */
	Public static function traffic($road_name = null,$city = null,$ak = null,$options = []){
		$map = array_merge(['road_name'=>$road_name,'city'=>$city,'ak'=>$ak],$options);
		try{
			$queryParams = self::toBuildParams($map);
			$result = self::request(self::HostURL.self::TrafficURL.$queryParams);
			if($result['err'] == true) return $result;
			return self::appResult($result['info'],$result['data'],false);
		} catch(\Exception $err){
			return self::appResult($err->getMessage());
		}
	}
	/**
	 * [toBuildParams 参数转换]
	 * @Author    como
	 * @DateTime  2019-08-27
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     array      $arr [description]
	 * @return    [type]          [description]
	 */
	Protected static function toBuildParams($arr = []){
		$data = [];
		foreach($arr as $key=>$val){
			$data[] = $key.'='.$val;
		}
		return implode('&', $data);
	}
	/**
	 * [appResult 统一返回值]
	 * @Author    como
	 * @DateTime  2019-08-27
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     string     $info [description]
	 * @param     [type]     $data [description]
	 * @param     boolean    $err  [description]
	 * @return    [type]           [description]
	 */
	Protected static function appResult($info = '',$data = null,$err = true){
		return ['info'=>$info,'data'=>$data,'err'=>$err];
	}
}