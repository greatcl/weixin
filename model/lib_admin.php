<?php
/**
 * User: greatcl
 * Date: 14-7-8
 * Time: 下午3:15
 */

class lib_admin {
    /**
     * 获取公众号的accessToken
     */
    function getAccessToken($appId, $appSecret) {
        $mAppInfo = spClass('m_app_info');
        $time = time();
        $appInfo = $mAppInfo -> find(array('appId' => $appId, 'appSecret' => $appSecret), NULL, NULL, 1);
        if (!$appInfo) {// 查询无记录
            $json = file_get_contents(TOKEN_URL);
            $result = json_decode($json, true);
            $accessToken = $result['access_token'];
            $addResult = $mAppInfo -> create(array('appId' => $appId, 'appSecret' => $appSecret, 'accessToken' => $accessToken, 'accTokenExpireTime' => $time + 3600));
        } else if ($appInfo['accTokenExpireTime'] < $time) {// accessToken已过期
            $json = file_get_contents(TOKEN_URL);
            $result = json_decode($json, true);
            $accessToken = $result['access_token'];
            $updateResult = $mAppInfo -> update(array('appId' => $appId), array('accessToken' => $accessToken, 'accTokenExpireTime' => $time + 3600));
        } else {
            $accessToken = $appInfo['accessToken'];
        }
        return $accessToken;
    }
} 