<?php
/**
 * 抓取图片的类
 * 文件夹名称是源文件名称,文件名称是url后缀
 */
class GetPic{
    private $file;
    private $picArray = array();
    public function __construct(){
        $this->file = "images/";
        $this->picArray = array(
            'jpg', 'jpeg', 'gif', 'png', 'bmp'
        );
    }    

    public function done($path) {
        $fileName = array_shift( explode(".", $path));
        if( !is_dir($this->file.$fileName) ) {
            mkdir($this->file.$fileName);   
        }
        $fileName = $this->file.$fileName;
        if( file_exists($path)) {
            $lists = json_decode( file_get_contents($path), true);
            foreach($lists as $sequence => $item) {
                if($this->checkPic($item)) {
                    if( strstr($item, "wx_fmt")) {
                        $url = array_shift(explode("?", $item));    
                        $houzui = array_pop( explode("=", array_pop(explode("?", $item))));
                        $picName = explode("/", $url)[4].".".$houzui;
                    } else {
                        $url = $item;    
                    }
                    $strPic = $this->curlRequest($url)['response'];
                    error_log($strPic, '3', $fileName."/".$picName);
                }
                continue;
            } 
        }
          
    }
    
    //判断是否是合法URL
    public function checkPic($url) {
        if(preg_match('/http:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$url)){
            return true;
        } else {
            return false;    
        }
    }

    private function curlRequest($url, $postData = '', $timeOut = 300, $httpHeader = array(), $pox = '') {
        //$pox = '61.135.217.13:80';

        $handle = curl_init ();
        curl_setopt ( $handle, CURLOPT_URL, $url );
        if ($httpHeader) {
            curl_setopt($handle, CURLOPT_HTTPHEADER, $httpHeader);
        }
        curl_setopt($handle, CURLOPT_HTTPHEADER, Array("Content-Type: application/json"));  
        curl_setopt ( $handle, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $handle, CURLOPT_HEADER, 0 );                                                                
        curl_setopt ( $handle, CURLOPT_TIMEOUT, $timeOut );
        curl_setopt ( $handle, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt ( $handle, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt ( $handle, CURLOPT_SSL_VERIFYHOST, false );
        //curl_setopt ( $handle, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_3_2 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Mobile/13F69 Html5Plus/1.0');
        //curl_setopt ( $handle, CURLOPT_ENCODING, 'gzip,deflate,sdch');
        //curl_setopt ( $handle, CURLOPT_REFERER, "http://www.yelp.com");  
        if( $pox ) {
            curl_setopt ( $handle, CURLOPT_PROXY, $pox);       
        }

        if (! empty ( $postData )) {
            curl_setopt ( $handle, CURLOPT_POST, TRUE );
            curl_setopt ( $handle, CURLOPT_POSTFIELDS, $postData);
        }
        $result['response'] = curl_exec ( $handle );
        $result['httpStatus'] = curl_getinfo ( $handle, CURLINFO_HTTP_CODE );
        $result['fullInfo'] = curl_getinfo ( $handle );
        $result['errorMsg'] = '';
        $result['errorNo'] = 0;
        if (curl_errno($handle)) {
            $result['errorMsg'] = curl_error($handle);
            $result['errorNo'] = curl_errno($handle);
        }
        curl_close ( $handle );
        return $result;
    } 






}











?>
