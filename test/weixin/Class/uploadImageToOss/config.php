<?php

const IMAGE_FIND_DEP = 2; //查找深度

const IMAGE_PATH = '/Users/lisuxiang/Desktop/test/weixin/images'; //图片目录

const IMAGE_PATH_FINISH = '/Users/lisuxiang/Desktop/test/weixin/finish'; //目标目录

const UPLOAD_OSS_PATH = 'topic/'; //oss路径

const MAP_FILE = '/Users/lisuxiang/Desktop/test/weixin/picKeys.txt'; //映射文件

const ERROR_LOG = '/Users/lisuxiang/Desktop/test/Class/uploadImageToOss/error.log'; //错误日志

static $filterFile = array(
    '.',
    '..',
    '.DS_Store'
);

static $allowExt = array(
    'jpg',
    'png',
    'jpeg',
    'gif'
);
