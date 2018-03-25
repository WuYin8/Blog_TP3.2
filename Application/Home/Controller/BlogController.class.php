<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\UserModel;
use Home\Model\DetailsModel;
use Home\Model\GalleryModel;
use Home\Model\LinkModel;
use Home\Model\MsgModel;
class BlogController extends Controller {
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

	// 博客列表页
	function blog()
	{
		// 通过传参获取分页
		$page = intval(I('get.page' , 1));
		$count = $this->detailsM->where(['first'=>1 , 'isdel'=>0])->count();
		$pageCount = ceil($count / 4);
		if ($pageCount == 0) {
			$pageCount = 1;
		}
		if ($page <= 0 || $page > $pageCount) {
			$this->error('当前页面不存在，正在返回……' , '/home/blog/blog' , 5);
			exit;
		}

		// 帖子与图片两表联查
		$details = $this->detailsM->join('blog_gallery ON blog_details.pid = blog_gallery.pid')->where(['blog_details.first'=>1 , 'blog_details.isdel'=>0])->order('blog_details.id desc')->page($page . ' , 4')->select();
		$this->assign('details' , $details);

		// 生成分页用的链接
		$pages['first'] = '/home/blog/blog?page=1';
		$pages['end'] = '/home/blog/blog?page=' . $pageCount;
		if ($page == 1) {
			$pages['prev'] = '/home/blog/blog?page=1';
		} else {
			$pages['prev'] = '/home/blog/blog?page=' . ($page - 1);
		}
		if ($page == $pageCount) {
			$pages['next'] = '/home/blog/blog?page=' . $pageCount;
		} else {
			$pages['next'] = '/home/blog/blog?page=' . ($page + 1);
		}
		$this->assign('pages' , $pages);

		//作者名字
		$author = $this->userM->field('username')->where(['undertype'=>1])->limit(1)->select();
		$this->assign('author' , $author[0]['username']);

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