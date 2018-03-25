<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\UserModel;
class UserController extends Controller
{
	protected $userM;
	function __construct ()
	{
		parent::__construct();  
		$this->userM = M("User");
	}
	//登录判断
	function login()
	{
		$loginName = I('post.loginName');
		$loginPwd = md5(I('post.loginPwd'));

		// 用户名判断
		$loginNameExists = $this->userM->field('uid')->where(['username'=>$loginName])->select();
		if (!$loginNameExists) {
			$this->error('用户名不存在' , '/home/user/user' , 5);
			exit;
		}

		// 用户锁定判断
		$loginNameDel = $this->userM->field('allowlogin')->where(['username'=>$loginName])->select();
		if ($loginNameDel[0]['allowlogin'] == '1') {
			$this->error('用户名被锁定，请联系管理员处理' , '/home/user/user' , 5);
			exit;
		}

		// 密码正确判断
		$oldPwd = $this->userM->field('password')->where(['username'=>$loginName])->select();
		if ($loginPwd !== $oldPwd[0]['password']) {
			$this->error('密码不正确，请重试' , '/home/user/user' , 5);
			exit;
		}

		// 登录成功，生成session
		$this->success('登录成功，正在返回首页' , '/index' , 5);
		$uInfo = $this->userM->field()->where(['username'=>$loginName])->limit(1)->select();
		session('username' , $loginName);
		session('uid' , $uInfo[0]['uid']);
		session('pic' , $uInfo[0]['picture']);
		session('grade' , $uInfo[0]['undertype']);
		exit;
	}
	//登出
	function logout()
	{
		// 销毁session
		session(null);
		$this->success('退出成功，正在返回首页' , '/index' , 5);
	}
	//注册判断
	function register()
	{
		// 验证码获取判断
		if (empty(I('session.code'))) {
			$this->error('验证码获取失败，请重试' , '/home/user/user' , 5);
			exit;
		}
		$scode = I('session.code');
		$registerName = I('post.registerName');

		// 用户名判断
		$registerNameExists = $this->userM->field('uid')->where(['username'=>$registerName])->select();
		if ($registerNameExists) {
			$this->error('用户名已存在' , '/home/user/user' , 5);
			exit;
		}
		$registerPwd = I('post.registerPwd');
		$phone = I('post.phone');

		// 手机号判断
		$registerPhoneExists = $this->userM->field('phone')->where(['username'=>$registerName])->select();
		if ($registerPhoneExists) {
			$this->error('手机号已被注册' , '/home/user/user' , 5);
			exit;
		}

		// 验证码正确判断
		$code = I('post.code');
	    	if($code !== $scode){
	    		$this->error('验证码不正确' , '/home/user/user' , 5);
			exit;
		}

		// 用户数据插入数据库
		$this->userM->data(['username'=>$registerName , 'password'=>md5($registerPwd) , 'phone'=>$phone])->add();
	    	$this->success('注册成功，转到登录界面' , '/home/user/user' , 5);
		exit;
    		
	}
	//接口用
	function code()
	{
		include "Ucpaas.php";
		//初始化必填
		$options['accountsid']='19a6efc75833a611174be919347c4e48';
		$options['token']='0cebe3d404b6405cba9412dcdaac438c';

		//初始化 $options必填
		$ucpass = new Ucpaas($options);

		//开发者账号信息查询默认为json或xml
		header("Content-Type:text/html;charset=utf-8");


		//封装验证码
		$str = '1234567890123567654323894325789';
		$code = substr(str_shuffle($str),0,5);
		$_SESSION['code']=$code;
		//短信验证码（模板短信）,默认以65个汉字（同65个英文）为一条（可容纳字数受您应用名称占用字符影响），超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。
		$appId = "5957fe0cfb234f68bff623c7fcded2f8";
		//给那个手机号发送
		$to = $_GET['phone'];

		$templateId = "251655";
		//这就是验证码
		$param=$code;

		echo $ucpass->templateSMS($appId,$to,$templateId,$param);
	}
	//默认页面
	function user()
	{
		if (!empty($_SESSION['username'])) {
			$this->error('当前已登录' , '/index' , 5);
		}	
		$this->display();
	}


}