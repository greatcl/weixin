<?php

/**
 * Class admin
 * 用于管理员后台操作，包括查看
 */
class admin extends adminController
{
    function __construct(){
		parent::__construct();
	}

    public function index(){
        echo "Admin";
    }

    /**
     * 菜单管理页面
     */
    public function menuManage(){
        $accessToken = spClass('lib_admin')->getAccessToken(APPID, APPSECRET);
        $getMenusUrl = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token=' . $accessToken;
        $currentMenus = file_get_contents($getMenusUrl);
        echo $currentMenus;
    }

    /**
     * 创建菜单
     */
    public function createMenu(){
        $menus = array(
            'button' => array(
                array(
                    'name' => urlencode('图文消息'),
                    'sub_button' => array(
                        array(
                            'type' => 'click',
                            'name' => urlencode('纯文本'),
                            'key' => 'plainText'
                        ),
                        array(
                            'type' => 'click',
                            'name' => urlencode('图文（一）'),
                            'key' => 'news1'
                        ),
                        array(
                            'type' => 'click',
                            'name' => urlencode('图文（二）'),
                            'key' => 'news2'
                        )
                    )
                ),
                array(
                    'name' => urlencode('多媒体消息'),
                    'sub_button' => array(
                        array(
                            'type' => 'click',
                            'name' => urlencode('音频'),
                            'key' => 'voice'
                        ),
                        array(
                            'type' => 'click',
                            'name' => urlencode('歌曲'),
                            'key' => 'music'
                        ),
                        array(
                            'type' => 'click',
                            'name' => urlencode('视频'),
                            'key' => 'video'
                        )
                    )
                ),
               array(
                   'name' => urlencode('关于'),
                   'sub_button' => array(
                       array(
                           'type' => 'view',
                           'name' => urlencode('关于我'),
                           'url' => 'http://greatcl.com/index.php/about/'
                       ),
                       array(
                           'type' => 'view',
                           'name' => urlencode('首页'),
                           'url' => 'http://greatcl.com'
                       )
                   )
               )
            )
        );

        // 获取accessToken
        $accessToken = spClass('lib_admin') -> getAccessToken(APPID, APPSECRET);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$accessToken");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, urldecode(json_encode($menus)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        echo $tmpInfo;
    }

    /*
     * 删除菜单
     */
    public function deleteMenu(){
        $accessToken = spClass('lib_admin')->getAccessToken(APPID, APPSECRET);
        $deleteMenuUrl = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=' . $accessToken;
        $currentMenus = file_get_contents($deleteMenuUrl);
        echo $currentMenus;
    }
}