<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\UserModel;
use Home\Model\DetailsModel;
use Home\Model\GalleryModel;
use Home\Model\LinkModel;
use Home\Model\MsgModel;
class AdminController extends Controller {
	public $userM;
	public $detailsM;
	public $galleryM;
	public $linkM;
	public $msgM;
	function __construct ()
	{
		parent::__construct();  
		$this->userM = M("User");
		$this->detailsM = M("Details");
		$this->galleryM = M("Gallery");
		$this->linkM = M("Link");
		$this->msgM = M("Msg");
	}

	// 默认登录页面的方法
	function login()
	{
		// 判断是否允许登录
		if (!empty($_POST)) {
			$username = I('post.username');
			$password = md5(I('post.password'));
			$nameEixsts =$this->userM->field('uid')->where(['username'=>$username])->limit(1)->select();
			// 判断用户名是否存在
			if ($nameEixsts) {
				
			} else {
				$this->error('用户名不存在' , '/home/admin/login' , 5);
				exit;
			}

			// 判断是否管理员
			$isAdmin = $this->userM->field('undertype')->where(['username'=>$username])->limit(1)->select();
			if ($isAdmin[0]['undertype'] != 1) {
				$this->error('需要管理员权限' , '/home/admin/login' , 5);
				exit;
			}
			
			// 判断密码正确	
			$pwdAdmin = $this->userM->field('password')->where(['username'=>$username])->limit(1)->select();
			if ($password != $pwdAdmin[0]['password']) {
				$this->error('密码不正确，请重试' , '/home/admin/login' , 5);
				exit;
			}

			// 判断完成，允许登录，设置一套和前台区分的session
			$adminInfo = $this->userM->where(['username'=>$username])->limit(1)->select();
			session('adminName' , $username);
			session('adminUid' , $adminInfo[0]['uid']);
			session('adminPic' , $adminInfo[0]['picture']);
			session('adminUndertype' , $adminInfo[0]['undertype']);
			header('Location:/home/admin/index');
			exit;
		}

		// 展示默认登录界面
		$this->display();
	}

	// 首页默认为用户管理界面
	function index()
	{
		// 判断用户是否允许浏览
		if (empty(session('adminUndertype')) || session('adminUndertype') != '1') {
			$this->error('需要管理员权限' , '/home/admin/login' , 5);
			exit;
		}

		// 通过传参获取分页
		$page = intval(I('get.page' , 1));
		$count = $this->userM->where(['allowlogin'=>0])->count();
		$pageCount = ceil($count / 10);
		if ($pageCount == 0) {
			$pageCount = 1;
		}
		if ($page <= 0 || $page > $pageCount) {
			$this->error('当前页面不存在，正在返回……' , '/home/admin/index' , 5);
			exit;
		}

		// 展示用户管理界面，同时配合分页
		$users = $this->userM->where(['allowlogin'=>0])->order('uid asc')->page($page . ' , 10')->select();
		$this->assign('users' , $users);

		// 生成分页用的链接
		$pages['first'] = '/home/admin/index?page=1';
		$pages['end'] = '/home/admin/index?page=' . $pageCount;
		if ($page == 1) {
			$pages['prev'] = '/home/admin/index?page=1';
		} else {
			$pages['prev'] = '/home/admin/index?page=' . ($page - 1);
		}
		if ($page == $pageCount) {
			$pages['next'] = '/home/admin/index?page=' . $pageCount;
		} else {
			$pages['next'] = '/home/admin/index?page=' . ($page + 1);
		}
		$this->assign('pages' , $pages);

		$this->display();
	}

	// 登出方法
	function logout()
	{
		// 销毁所有的session选项
		session(null);
		$this->success('操作成功，正在退出……' , '/home/admin/login' , 5);
		exit;
	}

	// 锁定用户
	function userDel()
	{
		// 判断用户是否允许浏览
		if (empty(session('adminUndertype')) || session('adminUndertype') != '1') {
			$this->error('需要管理员权限' , '/home/admin/login' , 5);
			exit;
		}

		// 判断是锁定操作
		if (!empty(I('post.uid'))) {
			$displayID = join(',' , I('post.uid'));
			$userDel = $this->userM->where('uid in (' . $displayID . ')')->save(['allowlogin'=>'1']);
			if ($userDel) {
				header('Location:/home/admin/index');
				exit;
			} else {
				$this->error('修改失败' , '/home/admin/index' , 5);
				exit;
			}
		}

		// 判断为显示已锁定用户的界面,同时配合分页
		// 通过传参获取分页
		$page = intval(I('get.page' , 1));
		$count = $this->userM->where(['allowlogin'=>1])->count();
		$pageCount = ceil($count / 10);
		if ($pageCount == 0) {
			$pageCount = 1;
		}
		if ($page <= 0 || $page > $pageCount) {
			$this->error('当前页面不存在，正在返回……' , '/home/admin/userDel' , 5);
			exit;
		}

		// 展示用户管理界面，同时配合分页
		$users = $this->userM->where(['allowlogin'=>1])->order('uid asc')->page($page . ' , 10')->select();
		$this->assign('users' , $users);

		// 生成分页用的链接
		$pages['first'] = '/home/admin/userDel?page=1';
		$pages['end'] = '/home/admin/userDel?page=' . $pageCount;
		if ($page == 1) {
			$pages['prev'] = '/home/admin/userDel?page=1';
		} else {
			$pages['prev'] = '/home/admin/userDel?page=' . ($page - 1);
		}
		if ($page == $pageCount) {
			$pages['next'] = '/home/admin/userDel?page=' . $pageCount;
		} else {
			$pages['next'] = '/home/admin/userDel?page=' . ($page + 1);
		}
		$this->assign('pages' , $pages);

		$this->display();
	}
	//解锁用户
	function userUndel()
	{
		// 判断用户是否允许浏览
		if (empty(session('adminUndertype')) || session('adminUndertype') != '1') {
			$this->error('需要管理员权限' , '/home/admin/login' , 5);
			exit;
		}

		// 判断是解锁操作
		if (!empty($_POST['uid'])) {
			$displayID = join(',' , I('post.uid'));
			$userDel = $this->userM->where('uid in (' . $displayID . ')')->save(['allowlogin'=>'0']);
			if ($userDel) {
				header('Location:/home/admin/userDel');
				exit;
			} else {
				$this->error('修改失败' , '/home/admin/userDel' , 5);
				exit;
			}
		}
		exit;
	}
	//删除用户
	function userShit()
	{
		// 判断用户是否允许浏览
		if (empty(session('adminUndertype')) || session('adminUndertype') != '1') {
			$this->error('需要管理员权限' , '/home/admin/login' , 5);
			exit;
		}

		if (empty(I('get.sid'))) {
			$this->error('未执行删除操作' , '/home/admin/login' , 5);
			exit;
		}

		$shitID = I('get.sid');
		// 管理员（博主）不能删除
		$undertype = $this->userM->field('undertype')->where(['uid'=>$shitID])->select();
		if ($undertype[0]['undertype'] == '1') {
			$this->error('管理员（博主）不能删除' , '/home/admin/login' , 5);
			exit;
		}

		// 删除用户
		$users = $this->userM->where(['uid'=>$shitID])->delete();
		// 同时删除该用户的回复
		$details = $this->detailsM->where(['authorid'=>$shitID])->delete();
		if ($users) {
			$this->success('用户删除成功' , '/home/admin/userDel' , 5);
			exit;
		} else {
			$this->error('用户删除失败' , '/home/admin/userDel' , 5);
			exit;
		}
	}

	//文章管理
	function content()
	{
		// 判断用户是否允许浏览
		if (empty(session('adminUndertype')) || session('adminUndertype') != '1') {
			$this->error('需要管理员权限' , '/home/admin/login' , 5);
			exit;
		}

		// 展示文章管理界面，同时配合分页
		// 通过传参获取分页
		$page = intval(I('get.page' , 1));
		$count = $this->detailsM->where(['first'=>1 , 'isdel'=>0])->count();
		$pageCount = ceil($count / 8);
		if ($pageCount == 0) {
			$pageCount = 1;
		}
		if ($page <= 0 || $page > $pageCount) {
			$this->error('当前页面不存在，正在返回……' , '/home/admin/content' , 5);
			exit;
		}

		// 展示用户管理界面，同时配合分页
		$contents = $this->detailsM->where(['first'=>1 , 'isdel'=>0])->order('id desc')->page($page . ' , 8')->select();
		$this->assign('contents' , $contents);

		// 生成分页用的链接
		$pages['first'] = '/home/admin/content?page=1';
		$pages['end'] = '/home/admin/content?page=' . $pageCount;
		if ($page == 1) {
			$pages['prev'] = '/home/admin/content?page=1';
		} else {
			$pages['prev'] = '/home/admin/content?page=' . ($page - 1);
		}
		if ($page == $pageCount) {
			$pages['next'] = '/home/admin/content?page=' . $pageCount;
		} else {
			$pages['next'] = '/home/admin/content?page=' . ($page + 1);
		}
		$this->assign('pages' , $pages);

		$this->display();
	}

	//文章回收
	function contentDel()
	{
		// 判断用户是否允许浏览
		if (empty(session('adminUndertype')) || session('adminUndertype') != '1') {
			$this->error('需要管理员权限' , '/home/admin/login' , 5);
			exit;
		}

		// 判断是回收文章操作
		if (!empty(I('post.id'))) {
			$displayID = join(',' , I('post.id'));
			// 连同回复一起回收
			$userDel = $this->detailsM->where('id in (' . $displayID . ')')->save(['isdel'=>'1']);
			$rDel = $this->detailsM->where('tid in (' . $displayID . ')')->save(['isdel'=>'1']);
			if ($userDel) {
				header('Location:/home/admin/content');
				exit;
			} else {
				$this->error('修改失败' , '/home/admin/content' , 5);
				exit;
			}
		}

		// 判断为显示已回收文章的界面,同时配合分页
		// 通过传参获取分页
		$page = intval(I('get.page' , 1));
		$count = $this->detailsM->where(['first'=>1 , 'isdel'=>1])->count();
		$pageCount = ceil($count / 8);
		if ($pageCount == 0) {
			$pageCount = 1;
		}
		if ($page <= 0 || $page > $pageCount) {
			$this->error('当前页面不存在，正在返回……' , '/home/admin/contentDel' , 5);
			exit;
		}

		// 展示用户管理界面，同时配合分页
		$contents = $this->detailsM->where(['first'=>1 , 'isdel'=>1])->order('id desc')->page($page . ' , 8')->select();
		$this->assign('contents' , $contents);

		// 生成分页用的链接
		$pages['first'] = '/home/admin/contentDel?page=1';
		$pages['end'] = '/home/admin/contentDel?page=' . $pageCount;
		if ($page == 1) {
			$pages['prev'] = '/home/admin/contentDel?page=1';
		} else {
			$pages['prev'] = '/home/admin/contentDel?page=' . ($page - 1);
		}
		if ($page == $pageCount) {
			$pages['next'] = '/home/admin/contentDel?page=' . $pageCount;
		} else {
			$pages['next'] = '/home/admin/contentDel?page=' . ($page + 1);
		}
		$this->assign('pages' , $pages);

		$this->display();
	}
	//文章恢复
	function contentUndel()
	{
		// 判断用户是否允许浏览
		if (empty(session('adminUndertype')) || session('adminUndertype') != '1') {
			$this->error('需要管理员权限' , '/home/admin/login' , 5);
			exit;
		}

		// 判断是恢复文章操作
		if (!empty(I('post.id'))) {
			$displayID = join(',' , I('post.id'));
			// 连同回复一起恢复
			$userDel = $this->detailsM->where('id in (' . $displayID . ')')->save(['isdel'=>'0']);
			$rDel = $this->detailsM->where('tid in (' . $displayID . ')')->save(['isdel'=>'0']);
			if ($userDel) {
				header('Location:/home/admin/contentDel');
				exit;
			} else {
				$this->error('修改失败' , '/home/admin/contentDel' , 5);
				exit;
			}
		}
		exit;
	}
	//文章删除
	function contentShit()
	{
		// 判断用户是否允许浏览
		if (empty(session('adminUndertype')) || session('adminUndertype') != '1') {
			$this->error('需要管理员权限' , '/home/admin/login' , 5);
			exit;
		}

		if (empty($_GET['sid'])) {
			$this->error('未执行删除操作' , '/home/admin/contentDel' , 5);
			exit;
		}

		$shitID = I('get.sid');
		$users = $this->detailsM->where(['id'=>$shitID])->delete();
		// 删除文章同时删除回复
		$content = $this->detailsM->where(['tid'=>$shitID])->delete();
		if ($users) {
			$this->success('删除文章成功' , '/home/admin/contentDel' , 5);
			exit;
		} else {
			$this->error('删除文章失败' , '/home/admin/contentDel' , 5);
			exit;
		}
	}

	//回复管理
	function reply()
	{
		// 判断用户是否允许浏览
		if (empty(session('adminUndertype')) || session('adminUndertype') != '1') {
			$this->error('需要管理员权限' , '/home/admin/login' , 5);
			exit;
		}

		// 展示回复管理界面，同时配合分页
		// 通过传参获取分页
		$page = intval(I('get.page' , 1));
		$count = $this->detailsM->where(['first'=>0 , 'isdel'=>0])->count();
		$pageCount = ceil($count / 15);
		if ($pageCount == 0) {
			$pageCount = 1;
		}
		if ($page <= 0 || $page > $pageCount) {
			$this->error('当前页面不存在，正在返回……' , '/home/admin/reply' , 5);
			exit;
		}

		// 展示用户管理界面，同时配合分页
		$replys = $this->detailsM->where(['first'=>0 , 'isdel'=>0])->order('id desc')->page($page . ' , 15')->select();
		$this->assign('replys' , $replys);

		// 生成分页用的链接
		$pages['first'] = '/home/admin/reply?page=1';
		$pages['end'] = '/home/admin/reply?page=' . $pageCount;
		if ($page == 1) {
			$pages['prev'] = '/home/admin/reply?page=1';
		} else {
			$pages['prev'] = '/home/admin/reply?page=' . ($page - 1);
		}
		if ($page == $pageCount) {
			$pages['next'] = '/home/admin/reply?page=' . $pageCount;
		} else {
			$pages['next'] = '/home/admin/reply?page=' . ($page + 1);
		}
		$this->assign('pages' , $pages);

		$this->display();
	}

	//回复回收
	function replyDel()
	{
		// 判断用户是否允许浏览
		if (empty(session('adminUndertype')) || session('adminUndertype') != '1') {
			$this->error('需要管理员权限' , '/home/admin/login' , 5);
			exit;
		}

		// 判断是回收文章操作
		if (!empty(I('post.id'))) {
			$displayID = join(',' , I('post.id'));
			$userDel = $this->detailsM->where('id in (' . $displayID . ')')->save(['isdel'=>'1']);
			if ($userDel) {
				header('Location:/home/admin/reply');
				exit;
			} else {
				$this->error('修改失败' , '/home/admin/reply' , 5);
				exit;
			}
		}

		// 判断为显示已回收文章的界面,同时配合分页
		// 通过传参获取分页
		$page = intval(I('get.page' , 1));
		$count = $this->detailsM->where(['first'=>0 , 'isdel'=>1])->count();
		$pageCount = ceil($count / 15);
		if ($pageCount == 0) {
			$pageCount = 1;
		}
		if ($page <= 0 || $page > $pageCount) {
			$this->error('当前页面不存在，正在返回……' , '/home/admin/contentDel' , 5);
			exit;
		}

		// 展示用户管理界面，同时配合分页
		$replys = $this->detailsM->where(['first'=>0 , 'isdel'=>1])->order('id desc')->page($page . ' , 15')->select();
		$this->assign('replys' , $replys);

		// 生成分页用的链接
		$pages['first'] = '/home/admin/replyDel?page=1';
		$pages['end'] = '/home/admin/replyDel?page=' . $pageCount;
		if ($page == 1) {
			$pages['prev'] = '/home/admin/replyDel?page=1';
		} else {
			$pages['prev'] = '/home/admin/replyDel?page=' . ($page - 1);
		}
		if ($page == $pageCount) {
			$pages['next'] = '/home/admin/replyDel?page=' . $pageCount;
		} else {
			$pages['next'] = '/home/admin/replyDel?page=' . ($page + 1);
		}
		$this->assign('pages' , $pages);

		$this->display();
	}
	//回复恢复
	function replyUndel()
	{
		// 判断用户是否允许浏览
		if (empty(session('adminUndertype')) || session('adminUndertype') != '1') {
			$this->error('需要管理员权限' , '/home/admin/login' , 5);
			exit;
		}

		// 判断是恢复回复操作
		if (!empty(I('post.id'))) {
			$displayID = join(',' , I('post.id'));
			// 连同回复一起恢复
			$userDel = $this->detailsM->where('id in (' . $displayID . ')')->save(['isdel'=>'0']);
			if ($userDel) {
				header('Location:/home/admin/replyDel');
				exit;
			} else {
				$this->error('修改失败' , '/home/admin/replyDel' , 5);
				exit;
			}
		}
		exit;
	}
	//回复删除
	function replyShit()
	{
		// 判断用户是否允许浏览
		if (empty(session('adminUndertype')) || session('adminUndertype') != '1') {
			$this->error('需要管理员权限' , '/home/admin/login' , 5);
			exit;
		}

		if (empty($_GET['sid'])) {
			$this->error('未执行删除操作' , '/home/admin/replyDel' , 5);
			exit;
		}

		$shitID = I('get.sid');
		$content = $this->detailsM->where(['id'=>$shitID])->delete();
		if ($content) {
			$this->success('删除回复成功' , '/home/admin/replyDel' , 5);
			exit;
		} else {
			$this->error('删除回复失败' , '/home/admin/replyDel' , 5);
			exit;
		}
	}


}