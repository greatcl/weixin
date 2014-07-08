<?php
class manager extends adminController {
	function __construct(){
		parent::__construct();
	}
	// 菜单数据
	private $menuData;
	
	/**
	 * 初始化菜单数据
	 * @todo 后期可改为数据库存储
	 */
	private function initMenuData(){
		$this->menuData = '{
		    "button":[
			   {
				   "name":"成绩单",
				   "sub_button":[
					{
				   		"type": "view",
				   		"name": "题库成绩",
				   		"url": "'.sprintf(URL_TEMPLATE, urlencode(DOMAIN."index.php?c=studycenter&a=tkReportEntry")).'"
					},
				   	{
				   		"type": "view",
				   		"name": "作业成绩",
				   		"url": "'.sprintf(URL_TEMPLATE, urlencode(DOMAIN."index.php?c=studycenter&a=hwReportEntry")).'"
				  	}
				   ]
		       },
			   {
		           "name": "订阅中心",
		           "type": "view",
		           "url": "'.sprintf(URL_TEMPLATE, urlencode(DOMAIN."index.php?c=parentcenter&a=orderCenter")).'"
		       },
		       {
		           "name":"更多",
		           "sub_button":[
			            {
			               "type":"view",
			               "name":"我的账号",
			               "url": "'.sprintf(URL_TEMPLATE, urlencode(DOMAIN."index.php?c=parentcenter&a=myAccount")).'"
			            },
			            {
			            	"type": "view",
			            	"name": "孩子账号",
			            	"url": "'.sprintf(URL_TEMPLATE, urlencode(DOMAIN."index.php?c=childcenter&a=myChildren")).'"
			            },
			            {
			               "type":"view",
			               "name":"关于我们",
			               "url": "'.DOMAIN."index.php?c=outter&a=aboutus".'"
			            }
		            ]
		       }
			]
		}';
	}

	/**
	 * 创建公众号菜单
	 */
	public function createMenu() {
		// 初始化菜单数据
		$this->initMenuData();
		// 获取accessToken
		$accessToken = spClass('lib_manager') -> getAccessToken(APPID, APPSECRET);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$accessToken");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->menuData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		if (curl_errno($ch)) {
			return curl_error($ch);
		}
		curl_close($ch);
		echo $tmpInfo;
	}

}
?>