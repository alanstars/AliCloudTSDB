## AliCloudTSDB
阿里云时序数据库HTTP请求API接口封装,因为公司项目着急使用，暂时先以能用为主，后期会不断完善更新

## 安装方法
```
composer require coolelephant/aliyun-tsdbapi
```
## 使用方法
本方法支持命名空间，如您的项目支持自动加载，直接实例化即可
```
$aliyunCloud = new \CoolElephant\AliCloudTSDB\AliCloudTSDB('username','password','ts-xxx.hitsdb.rds.aliyuncs.com:8242');
$response = $aliyunCloud->method('POST')->api('/api/suggest')->param(['type'=>'metrics'])->request();
```

如您的项目不支持自动加载，则需要再上面的基础上引入
```
require_once('../vendor/autoload.php');
```


返回结果已做处理，以array 数组的形式输出
```
[
    'resultcode'        =>  0,
    'resultdesc'        =>  'Success',
    'subscriptionId'    =>  'f36934ad-74b3-4666-85bc-05f0fbcb46f5',
    'relationNum'       =>  '+8616558940111',
    'callDirection'     =>  0,
    'duration'          =>  120,
    'maxDuration'       =>  1
];
```

## 具体返回值请查看官方接口
[点击查看官方文档](https://help.aliyun.com/document_detail/63557.html)