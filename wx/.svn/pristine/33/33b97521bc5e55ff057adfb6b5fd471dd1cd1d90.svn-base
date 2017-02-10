<?php
namespace Common\Library;
//微信操作类
class Wechat{
  private $user;       //用户的帐号
  private $dev;        //开发者帐号
  public  $MsgType;    //接收的消息类型
  private $CreateTime; //消息发送时间
  private $MsgId;      //消息ID
  private $postObj;    //接收到的文档
	private $responseTpl = array(    //回复消息模板
    //文本消息
    'text'=> "<xml>
			  <ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[text]]></MsgType>
				<Content><![CDATA[%s]]></Content>
				<FuncFlag>0</FuncFlag>
				</xml>",
    'image'=>"<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[image]]></MsgType>
        <Image>
        <MediaId><![CDATA[%s]]></MediaId>
        </Image>
        </xml>",
    'voice'=>"<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[voice]]></MsgType>
        <Voice>
        <MediaId><![CDATA[%s]]></MediaId>
        </Voice>
        </xml>",
    'video'=>"<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[video]]></MsgType>
        <Video>
        <MediaId><![CDATA[%s]]></MediaId>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        </Video> 
        </xml>",
    'music'=>"
        <xml>
          <ToUserName><![CDATA[%s]]></ToUserName>
          <FromUserName><![CDATA[%s]]></FromUserName>
          <CreateTime>%s</CreateTime>
          <MsgType><![CDATA[music]]></MsgType>
          <Music>
          <Title><![CDATA[%s]]></Title>
          <Description><![CDATA[%s]]></Description>
          <MusicUrl><![CDATA[%s]]></MusicUrl>
          <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
          </Music>
        </xml>",
          //     <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
  );
  public function __construct(){
    if( isset($_GET["echostr"]) ){    
      $echoStr = $_GET["echostr"];
      if($this->checkSignature()){
        echo $echoStr;
        exit;
      }
    }
    $this->getMsg();
  }
  
  public function robort(){
     
    if( $this->MsgType == 'text' ){
      $keyword = $this->postObj->Content;
      switch( $keyword ){
        case '学习':
          $this->responsenews();break;
        case '来首歌':
          $this->responsemusic('淡定','好听的尽情放送','http://moluo.applinzi.com/src/Yamin.mp3','http://moluo.applinzi.com/src/Yamin.mp3');break;
        default:
          $content = file_get_contents('http://www.tuling123.com/openapi/api?key=b84264cc645c7a78f7c71b68af24b773&info='.$keyword);
          $arr = json_decode($content);
          $this->responseMsg($arr->text);
      }
    }else if( $this->MsgType == 'event' ){
      $method = 'on'.$this->postObj->Event;
      if( method_exists( __CLASS__ , $method ) ){
        $this->$method();
        die;
      }
    }else{
      $this->responseMsg('你的节操掉了！');
    }
  }

  //获取票据
  public function getAccessToken($appid,$appsecret){
    $api = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}";
    return json_decode( $this->curl($api) );
  }

  //获取服务器IP地址

  public function getServerIP($access_token){
    $api = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=$access_token";
    return json_decode( $this->curl($api) );
  }


  //关注事件
  public function onsubscribe(){
      global $db;
      $sql = "insert into `admin` (`user`,`addtime`) values ('".$this->user."','".$this->CreateTime."')";
      
      //$sql = "update `admin` set `ltime`=".$this->CreateTime." where `user`='".$this->user."'";
      
      $db->exec($sql);
      $this->responseMsg('欢迎关注！');die;
  }

  //取消关注事件
  public function onunsubscribe(){
    //做解绑操作
      global $db;
      $sql = "update `admin` set `ltime`=".$this->CreateTime." where `user`='".$this->user."'";
      $db->exec($sql);
      die;
  }

  public function getMsg(){
    $postStr = file_get_contents('php://input');
    if( !empty($postStr) ){
      libxml_disable_entity_loader(true);
      $this->postObj  = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
      $this->user     = $this->postObj->FromUserName; // 发送方，用户帐号
      $this->dev      = $this->postObj->ToUserName;   // 接收方，开发者的公众号
      $this->MsgType  = $this->postObj->MsgType;      // 获取消息类型
      $this->CreateTime = $this->postObj->CreateTime; // 消息发送时间
    }
  }

  //回复文本消息
  public function responseMsg( $content ){
      $resultStr = sprintf($this->responseTpl['text'], $this->user,$this->dev, time(), $content);
      echo $resultStr;die;
  }

  //回复图片消息
  public function resonseImage($image){
    $resultStr = sprintf($this->responseTpl['image'], $this->user,$this->dev, time(), $image);
    echo $resultStr;die;
  }

  //回复语音消息
  public function resonseVoice($voice){
    $resultStr = sprintf($this->responseTpl['voice'], $this->user,$this->dev, time(), $voice);
    echo $resultStr;die;
  }

  //回复视频消息
  public function responsevideo($m_title,$m_desc,$desc){
    $resultStr = sprintf($this->responseTpl['video'], $this->user,$this->dev, time(), $video,$title,$desc);
    echo $resultStr;die;
  }

  //回复音乐消息
  public function responsemusic($title,$desc,$music_url,$QHmusic_url,$cover=null){
    $resultStr = sprintf($this->responseTpl['music'], $this->user,$this->dev, time(), $title,$desc,$music_url,$QHmusic_url);
    echo $resultStr;die;
  }

  public function responsenews($data=null){
    $tpl = "<xml>
      <ToUserName><![CDATA[%s]]></ToUserName>
      <FromUserName><![CDATA[%s]]></FromUserName>
      <CreateTime>%s</CreateTime>
      <MsgType><![CDATA[news]]></MsgType>
      <ArticleCount>%s</ArticleCount>
      <Articles>";
    foreach($data as $item ){
      $tpl .= "<item>";
      $tpl .= "<Title><![CDATA[{$item[0]}]]></Title>";
      $tpl .= "<Description><![CDATA[{$item[1]}]]></Description>";
      $tpl .= "<PicUrl><![CDATA[{$item[2]}]]></PicUrl>";
      $tpl .= "<Url><![CDATA[{$item[3]}]]></Url>";
      $tpl .= "</item>";
    }
    $tpl .= "</Articles>";
    $tpl .= "</xml>";
    $resultStr = sprintf($tpl, $this->user,$this->dev, time(), count($data) );
    echo $resultStr;die;
  }
		
	private function checkSignature(){
    if(!defined("TOKEN")) {
      throw new Exception('TOKEN is not defined!');
    }
        
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
    // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}

  public function curl($url,$data=[]){
    $curl = curl_init();
    curl_setopt($curl,CURLOPT_URL,$url);
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
    if($data){
      curl_setopt($curl,CURLOPT_POST,1);
      curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
    }
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
  }
}

?>