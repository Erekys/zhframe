<?php

class Http {
    public function curlRequest($url, $postData = '', $timeOut = 300, $httpHeader = array(), $pox = '') {
        //$pox = '61.135.217.13:80';

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
        //curl_setopt ( $handle, CURLOPT_USERAGENT,'mozilla/5.0 (iphone; cpu iphone os 5_1_1 like mac os x) applewebkit/534.46 (khtml, like gecko) mobile/9b206 micromessenger/5.0');
        curl_setopt ( $handle, CURLOPT_ENCODING, 'gzip,deflate,sdch');
        curl_setopt ( $handle, CURLOPT_REFERER, "http://weixin.qq.com/?version=369303328&uin=2131856544&nettype=1&scene=album_self");//"weixin.qq.com/*");  
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
