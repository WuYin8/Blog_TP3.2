<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\UserModel;
use Home\Model\DetailsModel;
use Home\Model\GalleryModel;
use Home\Model\LinkModel;
use Home\Model\MsgModel;
class IndexController extends Controller {
	protected $userM;
	protected $detailsM;
	protected $galleryM;
	protected $linkM;
	protected $msgM;
	function __construct ()
	{
		parent::__construct();  
		$this->userM = M("User");
		$this->detailsM = M("Details");
		$this->galleryM = M("Gallery");
		$this->linkM = M("Link");
		$this->msgM = M("Msg");
	}
	
    	//搜索展示
	function search()
	{
		$searchStr = $_GET['sMsg'];
		
		//对用户名，主贴标题，主贴内容，回复内容进行搜索
		//用户名和照片
		$searchUsername = $this->userM->where("username like '%$searchStr%'")->select();		
		$this->assign('searchUsername' , $searchUsername);

		// 标题和连接
		$searchTitle = $this->detailsM->where("title like '%$searchStr%' AND first = 1 AND isdel = 0")->select();
		$this->assign('searchTitle' , $searchTitle);

		// 主贴和连接
		$searchContent = $this->detailsM->where("content like '%$searchStr%' AND first = 1 AND isdel = 0")->select();
		$this->assign('searchContent' , $searchContent);

		// 回帖与连接
		$searchReply = $this->detailsM->where("content like '%$searchStr%' AND first = 0 AND isdel = 0")->select();
		$this->assign('searchReply' , $searchReply);

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

	function index()
	{
		//滚动画廊展示
		$galleryBig = $this->galleryM->order('pid asc')->limit(6)->select();
		$this->assign('galleryBig' , $galleryBig);

		//画廊展示
		$gallery = $this->galleryM->order('pid desc')->limit(8)->select();
		$this->assign('gallery' , $gallery);

		//近期的发帖
		$detailsThree = $this->detailsM->join('blog_gallery ON blog_details.pid = blog_gallery.pid')->where(['blog_details.first'=>'1' , 'blog_details.isdel'=>'0'])->order('id desc')->limit(3)->select();
		$this->assign('detailsThree' , $detailsThree);

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