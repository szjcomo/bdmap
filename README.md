# bdmap
这是一个百度地图组件
```
require '../vendor/autoload.php';
use szjcomo\bdmap\BdMap;
$ak = 'xxx';

//地址转经纬度
/*$data = BdMap::toLatlng('永和市场','河源市',$ak);
print_r($data);
*/

$data = file_get_contents('points1.json');
$options = json_decode($data,true);

//搜索一个坐标点是否在一组坐标点之内
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

```