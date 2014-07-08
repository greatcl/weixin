<?php
/**
 * User: greatcl
 * Date: 14-7-8
 * Time: 下午5:11
 */

class lib_user {
    /**
     * 创建订阅者关系
     */
    public function createSubscriber($appId, $openId){
        $mSubscriber = spClass('m_subscriber');
        $subscriberInfo = $mSubscriber->find(array('appId'=>$appId,'subscriberId'=>$openId),NULL,NULL,1);
        if (!$subscriberInfo){
            $mSubscriber->create(array('appId'=>$appId,'subscriberId'=>$openId));
        } else {
            $mSubscriber->update(array('appId'=>$appId,'subscriberId'=>$openId), array('active' => 1));
        }
    }

    /**
     * 更新取消订阅者用户状态
     */
    public function deleteSubscriber($appId, $openId){
        $mSubscriber = spClass('m_subscriber');
        $mSubscriber->update(array('appId'=>$appId,'subscriberId'=>$openId), array('active' => 0));
    }
} 