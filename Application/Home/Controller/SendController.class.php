<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\UserModel;
use Home\Model\DetailsModel;
use Home\Model\GalleryModel;
use Home\Model\LinkModel;
use Home\Model\MsgModel;
class SendController extends Controller {
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

	//发表帖子的判断
	function addDetails()
	{
		if (empty(I('session.grade'))) {
			$this->error('当前只允许博主发表博文' , '/index' , 5);
			exit;
		}
		if (I('session.grade') != '1') {
			$this->error('当前只允许博主发表博文' , '/index' , 5);
			exit;
		}

		$title = I('post.title');
		$content = I('post.content');
		$pid = rand(0 , 9);
		$isSend = $this->detailsM->data(['first'=>1 , 'pid'=>$pid , 'tid'=>0 , 'authorid'=>session('uid') , 'title'=>$title , 'content'=>$content , 'addtime'=>time()])->add();
		if ($isSend == false) {
			$this->error('博文发表失败' , '/home/send/send' , 50);
			exit;
		}
		$this->success('博文发表成功' , "/home/single/single?sid=" . $isSend , 5);
		exit;
	}

	//默认展示
	function send()
	{
		if (empty(I('session.grade'))) {
			$this->error('当前只允许博主发表博文' , '/index' , 5);
			exit;
		}
		if (I('session.grade') != '1') {
			$this->error('当前只允许博主发表博文' , '/index' , 5);
			exit;
		}

		// 需要从数据库中筛选内的内容
		$webTitle = $this->msgM->field('content')->where(['name'=>'webTitle'])->select();
		$this->assign('webTitle' , $webTitle[0]['content']);

		$webName = $this->msgM->field('content')->where(['name'=>'webName'])->select();
		$this->assign('webName' , $webName[0]['content']);

		$webUrl = $this->msgM->field('content')->where(['name'=>'webUrl'])->select();
		$this->assign('webUrl' , $webUrl[0]['content']);

		$webInfo = $this->msgM->field('content')->where(['name'=>'webInfo'])->select();
		$this->assign('webInfo' , $webInfo[0]['content']);

		$webMore = $this->msgM->field('content')->where(['name'=>'webMore'])->select();
		$this->assign('webMore' , $webMore[0]['content']);

		$link = $this->linkM->select();
		$this->assign('link' , $link);

		$this->display();
	}
}