<?php
  header('Content-Type:text/html;charset=utf-8');
  require "Wechat.class.php";
  define("TOKEN", "welcome"); //定义微信的token令牌
  define('APPID','wx7bf4e41ed10985a8'); //定义微信的应用ID
  define('APPSECRET','62e677149dd14e1abf45ca996cb81bbb'); //定义微信的应用密钥
  
  define('CURRENT_TIME', time() ); //统一整站运行时间.

  $db = new pdo('mysql:host='.SAE_MYSQL_HOST_M.';port='.SAE_MYSQL_PORT.';dbname='.SAE_MYSQL_DB,SAE_MYSQL_USER,SAE_MYSQL_PASS);

  $w = new Wechat();

  //$wechatObj->robort();
  
  $sql = "select `expires_in`,`access_token`,`create_time` from `access` where `appid`='".APPID."'";
  $data = $db->query($sql)->fetch(PDO::FETCH_ASSOC);

  $expire = $data['expires_in'];
  $create  = $data['create_time'];
  $access_token = $data['access_token'];
  
  if ( CURRENT_TIME >= $create + $expire + 60 ){
    //去微信获取票据
    $data = $w->getAccessToken(APPID,APPSECRET);
    $expires_in   = $data->expires_in;
    $access_token = $data->access_token;
    //拿到以后，更新数据库里面那个
    $sql = "update `access` set `create_time`='".CURRENT_TIME."',`expires_in`='$expires_in',`access_token`='$access_token' where `appid`='".APPID."'";
    $db->exec($sql);
  }
  
  define('ACCESS_TOKEN',$access_token); // 票据

  $list = $w->getServerIP( ACCESS_TOKEN );
  print_r( $list );

  

  // 1480059503-7200
  
  // 1480052303


  //print_r( $data );
