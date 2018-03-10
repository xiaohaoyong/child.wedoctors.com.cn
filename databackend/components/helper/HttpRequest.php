<?php
namespace app\components\helper;
class HttpRequest
{
    protected $_ch ; //curl handler
    protected $_cookies = array();//设置的cookie
    protected $_headers = array();//设置的header
    protected $_code    = 0; //状态码
    protected $_msg;         //错误信息
    protected $_ssl = false; //是否用https请求
    protected $_methods = array('get','post','put','delete');//允许的请求方式
    protected $_data = null;//请求体内容
    protected $_times = 1 ;//请求失败重试次数（包括第一次请求）
    
    /**
     * @param string $url 请求访问的url
     * @param boolean $ssl 设置请求方式是否以https方式
     * @times int $times 请求失败时重试次数，最多3次
     * @return curl resouce
     */
    public function __construct($url='',$ssl=false,$times=1)
    {
        $this->_ch = curl_init($url);   
        if($ssl){
            $this->_ssl = $ssl;
        }
        if($times>1){
            $this->setTimes($times);
        }
        return $this; 
    }
    
     /**
     * @param boolean 设置请求方式是否以https方式
     * @return curl resouce
     */
    public function setSSL($boolean=true)
    {
        $this->_ssl = $boolean;
        return $this;
    }    
    
    /**
     * @param string $name cookie名称
     * @param string $value cookie 值
     * @return curl resouce
     */
    public function setCookie($name,$value)
    {
        $this->_cookies[$name] = $value;
        return $this;
    }
    
    /**
     * @param string $name header名称
     * @param string $value header值
     * @return curl resouce
     */
    public function setHeader($name,$value)
    {
        $this->_headers[$name] = $value;
        return $this;
    }
    
    /**
     * @param string|array $data 请求方法为post put delete 时可能需要设置
     * @return curl resouce
     */
    public function setData($data)
    {
        if($data){
            $this->_data = $data;
        }
        return $this;
    }
    
    /**
     * @param int 设置请求失败时重试次数
     * @return curl resouce
     */
    public function setTimes($times){
        $this->_times = min(3,intval($times));
        return $this;
    }
    
    /**
     * @todo 返回curl 可以自定义设置一些参数
     * @return curl resouce
     */
    public function getInstance()
    {
        return $this->_ch;
    }
    
    /**
     * @todo 返回执行结果状态码
     * @return int
     */
    public function getCode()
    {
        return $this->_code;
    }
    
    /**
     * @todo 返回执行的错误提示信息
     * @return int
     */
    public function getMsg()
    {
        return $this->_msg;
    }
    
    /**
     * 初始化curl选项
     */
    protected function init($method,$url=null)
    {
        if($url){
            curl_setopt($this->_ch,CURLOPT_URL,$url);
        }
        curl_setopt($this->_ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($this->_ch,CURLOPT_HEADER,false);
        curl_setopt($this->_ch,CURLOPT_AUTOREFERER,true);
        curl_setopt($this->_ch,CURLOPT_CONNECTTIMEOUT,5);
        curl_setopt($this->_ch,CURLOPT_TIMEOUT,5);
        curl_setopt($this->_ch,CURLOPT_FOLLOWLOCATION,true);
        curl_setopt($this->_ch,CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
        if($this->_ssl){
            curl_setopt($this->_ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($this->_ch, CURLOPT_SSL_VERIFYPEER, 0);
        }
        if($method == 'get'){
            curl_setopt($this->_ch,CURLOPT_HTTPGET,true);
        }else if($method == 'post') {
            curl_setopt($this->_ch,CURLOPT_POST,true);
            if(!$this->_data) $this->_data = array();
            curl_setopt($this->_ch,CURLOPT_POSTFIELDS,$this->_data);
        }else {
            curl_setopt($this->_ch,CURLOPT_CUSTOMREQUEST,strtoupper($method));
            if($this->_data){
                $fields = (is_array($this->_data)) ? http_build_query($this->_data) : $this->_data;
                $this->_headers['Content-Length'] = strlen($fields); 
                curl_setopt($this->_ch,CURLOPT_POSTFIELDS,$fields);
            }
        }
        $headers = array();
        foreach($this->_headers as $k=>$v){
            $headers[] = "$k:$v";
        }
        $cookie = array();
        foreach($this->_cookies as $k=>$v)
        {
            $cookie[] = "$k=$v";
        }
        if($cookie){
            curl_setopt($this->_ch,CURLOPT_COOKIE,implode('; ',$cookie));
        }
        if($headers){
            curl_setopt($this->_ch,CURLOPT_HTTPHEADER,$headers);
        }        
    }
    
    protected function execute()
    {
        while($this->_times > 0){
            $result = curl_exec($this->_ch);
            if($this->_code = curl_errno($this->_ch)){
                $this->_msg = curl_error($this->_ch);
            }else{
                $this->_code = curl_getinfo($this->_ch,CURLINFO_HTTP_CODE);
            }
            $this->_times--;
            if($this->_code == 200){   
                break;
            }
        }
        if($this->_code != 200){
            return $result;
        }
        return $result;
    }
    
    
    public function __call($method,$params)
    {
        $method = strtolower($method);
        if(!in_array($method,$this->_methods)){
            $this->_msg = "请求方式不正确";
            return false;
        }
        $url = isset($params[0]) ? $params[0] : null;
        $this->init($method,$url);
        return $this->execute();
    }   
}
