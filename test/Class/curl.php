<?php

class Http {
    public function curlRequest($url, $postData = '', $timeOut = 300, $httpHeader = array(), $pox = '') {
        //$pox = '162.218.182.5:8080';

        $handle = curl_init ();
        curl_setopt ( $handle, CURLOPT_URL, $url );
        if ($httpHeader) {
            curl_setopt($handle, CURLOPT_HTTPHEADER, $httpHeader);
        }
        //curl_setopt($handle, CURLOPT_HTTPHEADER, Array("Content-Type: image/wxpc"));  
        curl_setopt ( $handle, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $handle, CURLOPT_HEADER, 0 );                                                                
        curl_setopt ( $handle, CURLOPT_TIMEOUT, $timeOut );
        curl_setopt ( $handle, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt ( $handle, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt ( $handle, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt ( $handle, CURLOPT_USERAGENT,'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36');
        curl_setopt ( $handle, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt ( $handle, CURLOPT_REFERER, "http://fendou.itcast.cn/article/look/aid/394");
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
