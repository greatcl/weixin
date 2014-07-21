<?php
class message extends spController
{
    private $fromUserName;
    private $toUserName;

    function __construct(){
		parent::__construct();
	}

    /**
     * 微信消息交互入口
     * 微信服务器所有的消息及事件推送的URL
     */
    public function entry(){
        // 验证微信接口配置信息
        if(isset($_GET['echostr']) && isset($_GET['nonce']) && isset($_GET['timestamp']) && isset($_GET['signature'])){
            $this -> valid();
        }
        // 响应消息及事件推送
        $this->responseMsg();
    }

    private function responseMsg() {
        //获取post数据
        $postStr = $GLOBALS['HTTP_RAW_POST_DATA'];

        //解析数据
        if (!empty($postStr)) {
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this -> fromUserName = $postObj -> FromUserName;
            $this -> toUsername = $postObj -> ToUserName;
            $reqMsgType = $postObj -> MsgType;

            switch ($reqMsgType) {
                // 事件
                case 'event' :
                    $msgEvent = $postObj -> Event;
                    $eventKey = $postObj -> EventKey;
                    $this -> handleEvent($msgEvent, $eventKey);
                    break;
                // 文本消息
                case 'text' :
                    $keyword = trim($postObj -> Content);
                    $this -> handleText($keyword);
                    break;
                default :
                    break;
            }
        }
    }

    /**
     * 获取回复的文本消息
     */
    private function getResponseText($contentStr) {
        $resultStr = '<xml>
					<ToUserName><![CDATA[' . $this -> fromUserName . ']]></ToUserName>
					<FromUserName><![CDATA[' . $this -> toUserName . ']]></FromUserName>
					<CreateTime>' . time() . '</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[' . $contentStr . ']]></Content>
					</xml>';
        return $resultStr;
    }

    /**
     * 获取回复的图文消息
     * @todo 未完善
     */
    private function getResponseNews($newsList) {
        $newsTpl = '<xml>
					<ToUserName><![CDATA[' . $this -> fromUserName . ']]></ToUserName>
					<FromUserName><![CDATA[' . $this -> toUserName . ']]></FromUserName>
					<CreateTime>' . time() . '</CreateTime>
					<MsgType><![CDATA[news]]></MsgType>
					<ArticleCount>1</ArticleCount>
					<Articles>
					<item>
					<Title><![CDATA[%s]]></Title>
					<Description><!CDATA[%s]></Description>
					<PicUrl><![CDATA[%s]]></PicUrl>
					<Url><![CDATA[%s]]></Url>
					</item>
					</Articles>
					</xml>';
        $resultNews = sprintf($newsTpl, $fromUsername, $toUsername, $time);
        return $resultNews;
    }

    /**
     * 处理接收到的文本消息
     */
    private function handleText($keyword) {
        if (!empty($keyword)) {
            $contentStr = "你发送的内容为：$keyword";
        } else {
            $contentStr = '不要输入空消息哦～';
        }
        echo $this -> getResponseText($contentStr);
    }

    /**
     * 处理接收到的事件推送
     */
    private function handleEvent($msgEvent, $eventKey) {
        switch ($msgEvent) { // 判断事件类型
            case 'subscribe' : // 用户关注订阅事件
                $url = 'www.baidu.com';
                $contentStr = "欢迎使用微信平台，请<a href='$url'>点此</a>进入官网";
                echo $this -> getResponseText($contentStr);

                // 插入一条订阅者信息
                $this -> createSubscriber(APPID, $this -> fromUserName);
                break;
            case 'CLICK' : // 点击菜单事件
                switch ($eventKey) {
                    case 'help' :
                        $contentStr = '';
                        echo $this -> getResponseText($contentStr);
                        break;
                    default :
                        $contentStr = '你点击的菜单未定义';
                        echo $this -> getResponseText($contentStr);
                        break;
                }
                break;
            case 'VIEW' :
                $msgType = 'text';
                $contentStr = $eventKey;
                break;
            case 'LOCATION' :
                break;
            case 'unsubscribe' : // 取消订阅
                $this->deleteSubscriber(APPID, $this->fromUserName);
                break;
            default :
                $msgType = 'text';
                $contentStr = 'OTHERS';
                break;
        }
    }

    /**
     * 插入一条订阅者信息
     * @param $appId // 公众号唯一标识
     * @param $openId // 微信用户对应该公众号的标识
     *
     */
    private function createSubscriber($appId, $openId) {
        spClass('lib_user') -> createSubscriber($appId, $openId);
    }

    /**
     * 删除取消订阅者的信息
     * @param $appId // 公众号唯一标识
     * @param $openId // 微信用户对应该公众号的标识
     *
     */
    private function deleteSubscriber($appId, $openId){
        spClass('lib_user')->deleteSubscriber($appId, $openId);
    }

    /**
     * 验证微信配置URL的正确性
     */
    private function valid() {
        $echoStr = $_GET['echostr'];

        if ($this -> checkSignature()) {
            die($echoStr);
        }
    }

    /**
     * 检验签名
     */
    private function checkSignature() {
        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}