<?php
// +----------------------------------------------------------------------
// | CoolCms [ DEVELOPMENT IS SO SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2019 http://www.coolcms.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +---------------------------------------------------------------------
// | Author: Alan <251956250@qq.com>
// +----------------------------------------------------------------------
// | DateTime: 2020/8/10 14:54
// +----------------------------------------------------------------------
// | Desc: 
// +----------------------------------------------------------------------

namespace CoolElephant\AliCloudTSDB;


use GuzzleHttp\Client;
use GuzzleHttp\Promise\AggregateException;

/**
 * 封装阿里云时序数据库TSDB的HTTP请求
 * 说明文档：https://help.aliyun.com/document_detail/63557.html?spm=a2c4g.11186623.6.646.26e41a05avR0vh
 * Class AliCloudTSDB
 * @package CoolElephant\AliCloud
 */
class AliCloudTSDB
{
    /**
     * 用户名
     * @var null
     */
    private $username = null;
    /**
     * 密码
     * @var null
     */
    private $password = null;
    /**
     * TSDB 实例访问链接.
     * @var null
     */
    private $hosts = null;
    /**
     * 请求方法，包含GET,POST,PUT,DELETE
     * @var string
     */
    private $method = 'GET';
    /**
     * TSDB API 支持通过添加查询字符串参数 method_override 来替代 HTTP methods/verbs
     * 使用方法，作为参数直接放到url后面
     * 包含：method_override=post,method_override=put,method_override=delete,其中get默认不用上传
     * @var null
     */
    private $method_override = null;
    /**
     * 具体的API接口url
     * @var null
     */
    private $api = null;
    /**
     * 具体请求参数
     * @var null
     */
    private $param = null;

    /**
     * 是否开启debug模式
     * @var bool
     */
    private $isDebug = false;

    /**
     * AliCloudTSDB constructor.
     * @param null $username
     * @param null $password
     * @param null $hosts
     */
    public function __construct($username=null,$password=null,$hosts=null,$isDebug = false)
    {
        if(empty($username) || empty($password) || empty($hosts)){
            return ['code'=>4001,'message'=>'必传参数不能为空，请检查username,password或hosts参数是否为空'];
            die;
        }
        $this->username = $username;
        $this->password = $password;
        if(stripos($hosts,'http') === 0){
            $this->hosts = $hosts;
        }else{
            $this->hosts = 'http://'.$hosts;
        }
        $this->isDebug = $isDebug;

    }

    /**
     * 请求
     * @return array|\Exception|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request(){
        if(empty($this->method) || empty($this->api) || empty($this->param)){
            return ['code'=>4005,'message'=>'必传参数不能为空，请检查method,api或param参数是否为空'];
            die;
        }
        $this->method_override = $this->switchToMethod($this->method);

        $url = $this->api.'?'.$this->method_override;
        try{
            $client = new Client(['base_uri'=>$this->hosts]);
            $option = [
                'headers'        => [
                    'Accept-Encoding' => 'gzip',
                    'Accept'     => 'application/json',
                    ],
                'decode_content'    =>  true,
                'auth'  =>  [$this->username,$this->password],
                'body'  =>  $this->param,
                'debug' => $this->isDebug
            ];
            $response = $client->request($this->method,$url,$option);
//            file_get_contents($url,false,stream_context_create($this->header()));
            return $response;
        }catch (\Exception $exception){
            return ['code'=>$exception->getCode(),'message'=>$exception->getMessage()];
        }catch (AggregateException $exception){
            return ['code'=>$exception->getCode(),'message'=>$exception->getMessage()];
        }
    }

    /**
     * 设置方法
     * @param $method
     * @return $this|array
     */
    public function method($method){
        if(empty($method)){
            return ['code'=>4002,'message'=>'必传参数不能为空，请检查method参数是否为空'];
            die;
        }
        $this->method = $method;
        return $this;
    }

    /**
     * 设置url
     * @param $api
     * @return $this|array
     */
    public function api($api){
        if(empty($api)){
            return ['code'=>4003,'message'=>'必传参数不能为空，请检查api参数是否为空'];
            die;
        }
        $this->api = $api;
        return $this;
    }

    /**
     * 设置请求参数
     * @param $param
     * @return $this|array
     */
    public function param($param){
        if(empty($param)){
            return ['code'=>4004,'message'=>'必传参数不能为空，请检查param参数是否为空'];
            die;
        }
        $this->param = $param;
        return $this;
    }

    /**
     * method转换成功method_override
     * @param $method
     * @return string
     */
    private function switchToMethod($method){
        $result = '';
        switch (strtolower($method)){
            case 'post':
                $result = 'method_override=post';
                break;
            case 'put':
                $result = 'method_override=put';
                break;
            case 'delete':
                $result = 'method_override=delete';
                break;
            default:
                $result = 'method_override=get';
                break;
        }
        return $result;
    }

}