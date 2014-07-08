<?php
class message extends spController
{
    private $fromUserName;
    private $toUserName;

    function __construct(){
		parent::__construct();
	}

    public function entry(){
        // 第一次验证微信接口配置信息时使用，验证后注释掉
 		$this -> valid();


    }

    private function responseMsg() {
        //获取post数据
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

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
        } else {
            echo "nothing";
            exit ;
        }
    }

    /**
     * 获取回复的文本消息
     */
    private function getResponseText($contentStr) {
        $resultStr = "<xml>
					<ToUserName><![CDATA[" . $this -> fromUserName . "]]></ToUserName>
					<FromUserName><![CDATA[" . $this -> toUserName . "]]></FromUserName>
					<CreateTime>" . time() . "</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[$contentStr]]></Content>
					</xml>";
        return $resultStr;
    }

    /**
     * 获取回复的图文消息
     * @todo 未完善
     */
    private function getResponseNews($newsList) {
        $newsTpl = "<xml>
					<ToUserName><![CDATA[" . $this -> fromUserName . "]]></ToUserName>
					<FromUserName><![CDATA[" . $this -> toUserName . "]]></FromUserName>
					<CreateTime>" . time() . "</CreateTime>
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
					</xml>";
        $resultNews = sprintf($newsTpl, $fromUsername, $toUsername, $time);
        return $resultNews;
    }

    /**
     * 处理接收到的文本消息
     */
    private function handleText($keyword) {
        if (!empty($keyword)) {
            $msgType = "text";
            $contentStr = "你发送的内容为：" . $keyword;
        } else {
            $msgType = "text";
            $contentStr = "不要输入空消息哦～";
        }
        // echo $this -> getResponseText($contentStr);
    }

    /**
     * 处理接收到的事件推送
     */
    private function handleEvent($msgEvent, $eventKey) {
        switch ($msgEvent) {
            case "subscribe" :
                $msgType = "text";
                $url = OAUTH_URL;
                $contentStr = "欢迎使用畅言门户家长助手微信平台，请绑定账号来使用更多功能<a href='$url'>绑定账号</a>";
                echo $this -> getResponseText($contentStr);
                // 插入一条订阅者信息
                $this -> createSubscriber(APPID, $this -> fromUserName);
                break;
            case "CLICK" :
                switch ($eventKey) {
                    case "usage" :
                        $contentStr = "您可以在您的个人学习门户个人中心中绑定孩子账号，在“更多”中将微信账号绑定我的门户账号，在成绩单中可以点击查询您的孩子题库成绩、作业成绩以及作业任务。";
                        echo $this -> getResponseText($contentStr);
                        break;
                    case "aboutus":
                        $contentStr = "关于我们：我是家长助手";
                        echo $this -> getResponseText($contentStr);
                        break;
                    case "feedback":
                        $contentStr = "请直接回复要反馈的内容，我们会尽快进行处理答复。";
                        echo $this -> getResponseText($contentStr);
                        break;
                    default :
                        $contentStr = "你点击的菜单未定义";
                        echo $this -> getResponseText($contentStr);
                        break;
                }
                break;
            case "VIEW" :
                $msgType = "text";
                $contentStr = $eventKey;
                break;
            case "LOCATION" :
                break;
            case "unsubscribe" :
                $this->deleteSubscriber(APPID, $this->fromUserName);
                break;
            default :
                $msgType = "text";
                $contentStr = "OTHERS";
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
        spClass('lib_parent_center') -> createSubscriber($appId, $openId);
    }

    /**
     * 删除取消订阅者的信息
     * @param $appId // 公众号唯一标识
     * @param $openId // 微信用户对应该公众号的标识
     *
     */
    private function deleteSubscriber($appId, $openId){
        spClass('lib_parent_center')->deleteSubscriber($appId, $openId);
    }

    /**
     * 验证微信配置URL的正确性
     */
    private function valid() {
        $echoStr = $_GET["echostr"];

        if ($this -> checkSignature()) {
            echo $echoStr;
            exit ;
        }
    }

    /**
     * 检验签名
     */
    private function checkSignature() {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

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