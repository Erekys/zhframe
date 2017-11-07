<?php
/**
 * 蚂蜂窝/微信文章抓取/图片下载/图片替换
 * @date 2016-11-25
 */
class Analy {
    private $conn; 
    private $path_list;
    private $path_mafengwo_data;
    private $path_weixin_data;
    private $path_data;
    private $path_image;
    private $flag;
    private $path_image_key;
    function __construct() {
        $this->curl = new Http();
        $this->flag = '马蜂窝';
        $this->path_mafengwo_data = "/Users/lisuxiang/Desktop/test/weixin/data/";
        $this->path_weixin_data = "/Users/lisuxiang/Desktop/test/weixin/data/";
        $this->path_data = "/Users/lisuxiang/Desktop/test/weixin/data/finish/";
        $this->path_image = "/Users/lisuxiang/Desktop/test/weixin/images/";
        $this->path_list = "/Users/lisuxiang/Desktop/test/weixin/data/list.json";
        $this->path_image_key = "/Users/lisuxiang/Desktop/test/weixin/picKeys.txt";
        include_once('Class/curl.php');
        include_once('Class/phpQuery.php');
        ini_set('memory_limit', '1024M');
    }

    //函数入口
    //文件必须先处理成特定格式的json
    //生成以m/w_点击数_用户ID为名的json
    public function enter($file) {
        $lists = json_decode(file_get_contents($file), true);    
        foreach($lists as $list) {
            var_dump($list[3]);
            $contents = array();
            $info = array(
                    'flat'      => $list[2],
                    'checkNum'  => $list[3],
                    'country'   => $list[4],
                    'city'      => $list[5],
                    'tags'      => $list[6],
                    'memberId'  => $list[7],
                    'url'       => $list[8],
                    );
            $infos[] = $info;
            if($info['flat'] == $this->flag) {
                $fileName = "m_".$info['checkNum']."_".$info['memberId'].".json";
                $content = $this->mafengwo($info['url']);
                $info['content'] = $content;
                $this->putInFile(json_encode($info), $this->path_mafengwo_data.$fileName);
            } else {
                $fileName = "w_".$info['checkNum']."_".$info['memberId'].".json";
                $content = $this->weixin($info['url']);    
                $info['content'] = $content;
                $this->putInFile(json_encode($info), $this->path_weixin_data.$fileName);
            }
        }
        $this->putInFile(json_encode($infos), $this->path_list);
    }

    public function mafengwo($url){
        $html = $this->getContents($url);
        phpQuery::newDocumentHTML($html, 'utf8');
        $title = pq(".headtext")->text();
        $createTime = pq(".time")->text();
        $items = array();
        foreach(pq("._j_master_content ._j_seqitem") as $info) {    
            $text = pq($info)->html();
            $img = pq($info)->find("img")->attr("data-src");
            $tips = pq($info)->find("span a")->text();
            if($img){
                $text = '';    
            }
            $items[] = array(
                    'value' => trim(strip_tags($text, "<br>")),
                    'img' => array_shift( explode("?", $img)),
                    'tips' => trim($tips)
                    );
        }
        if( !$items ) {
            $title = pq("h1")->text();
            foreach(pq("#pnl_contentinfo > p") as $info) {
                $img = pq($info)->find("img")->attr("data-src");  
                $tips = pq($info)->find("span a")->text();
                $value = '';
                if(pq($info)->attr('class') == "_j_note_content") {
                    $value = pq($info)->text();    
                }
                $items[] = array(
                        'value' => trim($value),
                        'img' => $img,
                        'tips' => trim($tips)
                        );
            }
        }

        /*
           foreach(array_filter($items) as $temp) {
           $img = $temp['img'];
           if( !$img ) {
           $values[] = $temp;    
           continue;    
           } else {
           $imgName = basename($img);
           $key = $images[$imgName];
           $image = "http://yp.storage.youpu.cn/".str_replace("_", "/", $key);
           $values[] = array(
           'value' => $temp['value'],
           'img' => $image,
           'tips' => $temp['tips']
           );
           }
           }*/
        $datas = array(
                'title' => trim($title),
                'createTime' => $createTime,
                'items' => array_filter($items) 
                );
        return $datas;
    }

    //微信数据处理
    public function weixin($url){
        $html = $this->getContents($url);
        phpQuery::newDocumentHTML($html, 'utf8');
        $title = pq("#activity-name")->text();
        $createTime = pq("#post-date")->text();
        foreach(pq("#js_content p") as $info) {    
            $img = pq($info)->find('img')->attr('data-src');
            $value = pq($info)->find('span:first')->text();    
            if( !$value ) {
                $value = pq($info)->find('strong')->text();
            }
            if($img || $value) {
                $items[] = array(
                        'img' => $img,
                        'value' => $value
                        );
            }
        }
        $datas = array(
                'title' => trim($title),
                'createTime' => $createTime,
                'items' => $items
                );
        return $datas;
    }

    public function getPic(){
        $lists = json_decode(file_get_contents($this->path_list), true);
        $path = $this->path_image;
        $num = 0;
        foreach($lists  as $list) {
            if( $list['flat'] == $this->flag) {
                $fileName = $this->path_mafengwo_data."m_".$list['checkNum']."_".$list['memberId'].".json" ; 
                $data = json_decode(file_get_contents($fileName), true);
                foreach($data['content']['items'] as $info) {
                    if($info['img']) {
                        $url = $this->formPic($info['img']);    
                        $imageName = basename($url);
                        if( filesize("images/".$imageName) > 1 ) {
                            continue;
                        }
                        var_dump($imageName);
                        $str = $this->getContents($url);
                        $this->putInFile($str, $path.$imageName);
                    }
                }
            } else {
                $fileName = $this->path_weixin_data."w_".$list['checkNum']."_".$list['memberId'].".json";    
                $data = json_decode(file_get_contents($fileName), true);
                foreach($data['content']['items'] as $info) {
                    if($info['img']) {
                        $url = $info['img'];    
                        if( strstr($url, "wx_fmt") ) {
                            $url = $this->formPic($url);    
                            $imageName = explode("/",$url)[4].".".array_pop(explode("=", $info['img']));
                        } else {
                            $imageName = explode("/", $url)[4].".jpeg";    
                        }
                        $imageName AND $num++;
                        if( filesize("images/".$imageName) > 1 ) {
                            continue;
                        }
                        var_dump($imageName);
                        $str = $this->getContents($url);
                        $this->putInFile($str, $path.$imageName);
                    }
                }
            }
        }
        var_dump($num);die;
    }

    public function formPic($url) {
        $return = '';
        if( strstr($url, "?") ) {
            $return  = array_shift( explode("?", $url)); 
        } else {
            $return = $url;    
        } 
        return $return;
    }

    //替换图片链接
    public function replaceImage() {
        $images = array(); 
        //生成对应关系
        foreach(file($this->path_image_key) as $info) {
            $arrInfo = explode("=", $info);
            $key = $arrInfo[1]; 
            $image = array_pop(explode("/", $arrInfo[0]));
            $images[$image] = trim($key);
        }   
        $lists = json_decode(file_get_contents($this->path_list), true);
        foreach($lists  as $list) {
            $values = array();
            if( $list['flat'] == $this->flag) {
                $fileName = $this->path_mafengwo_data."m_".$list['checkNum']."_".$list['memberId'].".json" ; 
                $finishName = $this->path_data."m_".$list['checkNum']."_".$list['memberId'].".json" ; 
                $data = json_decode(file_get_contents($fileName), true);
                foreach($data['content']['items'] as $info) {
                    if($info['img']) {
                        $img = basename($this->formPic($info['img']));
                        $info['img'] = "http://yp.storage.youpu.cn/".str_replace("_", "/",$images[$img]);
                        $values[] = $info;
                    } else {
                        $values[] = $info; 
                    }
                }
                $data['content']['items'] = $values;
                $this->putInFile(json_encode($data), $finishName);
            } else {
                $fileName = $this->path_weixin_data."w_".$list['checkNum']."_".$list['memberId'].".json";    
                $finishName = $this->path_data."w_".$list['checkNum']."_".$list['memberId'].".json";    
                $path = $this->path_weixin_image;
                $data = json_decode(file_get_contents($fileName), true);
                foreach($data['content']['items'] as $info) {
                    if($info['img']) {
                        $url = $info['img'];    
                        if( strstr($url, "wx_fmt") ) {
                            $url = $this->formPic($url);    
                            $img = explode("/",$url)[4].".".array_pop(explode("=", $info['img']));
                        } else {
                            $img = explode("/", $url)[4].".jpeg";    
                        }
                        $info['img'] = "http://yp.storage.youpu.cn/".str_replace("_", "/",$images[$img]);
                        $values[] = $info;
                    } else {
                        $values[] = $info;
                    }
                }
                $data['content']['items'] = $values;
                $this->putInFile(json_encode($data), $finishName);
            }

        }

    } 

    //获取html信息
    public function getContents($url) {
        $html = $this->curl->curlRequest($url); 
        return $html['response'];
    }

    public function putInFile($str, $fileName){
        $fp = fopen($fileName, 'wb');   
        fwrite($fp, $str);
        fclose($fp);
    }
}

?>
