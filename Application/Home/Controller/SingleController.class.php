<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\UserModel;
use Home\Model\DetailsModel;
use Home\Model\GalleryModel;
use Home\Model\LinkModel;
use Home\Model\MsgModel;
class SingleController extends Controller {
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

	//添加回复
	function addReply()
	{
		// 对回复的判断
		if (empty(I('get.sid'))) {
			$this->error('文章不存在' , '/home/blog/blog' , 5);
			exit;
		}
		$sid = I('get.sid');
		if (empty(I('session.uid'))) {
			$this->error('请登录后再回复' , '/home/single/single?sid=' . $sid , 5);
			exit;
		}

		// 添加回复
		$content = I('post.content');
		$isRepy = $this->detailsM->data(['tid'=>$sid , 'authorid'=>session('uid') , 'content'=>$content , 'addtime'=>time()])->add();
		if (!$isRepy) {
			$this->error('回复发表失败' , '/home/single/single?sid=' . $sid , 5);
			exit;
		}

		$this->success('回复发表成功' , '/home/single/single?sid=' . $sid , 5);
		exit;


	}
	//默认展示
	function single()
	{
		//要展示的主帖子信息
		if (empty(I('get.sid'))) {
			$this->error('这个文章被管理员吃掉了，去别的页面看看吧' , '/home/blog/blog' , 5);
			exit;
		} else {
			$id = I('get.sid');
		}
		// 判断id是否存在/屏蔽
		$result = $this->detailsM->field('isdel')->where(['id'=>$id])->select();
		if (!$result) {
			$this->error('这个文章被管理员吃掉了，去别的页面看看吧' , '/home/blog/blog' , 5);
			exit;
		} else if ($result[0]['isdel'] == 1) {
			$this->error('这个文章被管理员吃掉了，去别的页面看看吧' , '/home/blog/blog' , 5);
			exit;
		} else if ($result[0]['first'] == 1) {
			$this->error('这个文章被管理员吃掉了，去别的页面看看吧' , '/home/blog/blog' , 5);
			exit;
		}

		//点击量增加
		$hits = $this->detailsM->field('hits')->where(['id'=>$id])->select();
		$hits =intval($hits[0]['hits']);
		$hits = $hits + 1;
		$this->detailsM->where(['id'=>$id])->save(['hits'=>$hits]);
		$this->assign('hits' , $hits);
		
		// 发帖量更新
		$replyCount = $this->detailsM->where(['first'=>0 , 'isdel'=>0 , 'tid'=>$id])->count('id');
		$this->assign('replyCount' , $replyCount);

		// 主贴内容展示
		$details = $this->detailsM->join('blog_gallery ON blog_details.pid = blog_gallery.pid')->where(['blog_details.id'=>$id])->limit(1)->select();
		$this->assign('details' , $details);

		//作者名字和头像
		$author = $this->userM->join('blog_details ON blog_user.uid = blog_details.authorid')->where(['blog_details.id'=>$id])->limit(1)->select();
		$this->assign('author' , $author);

		// 回帖信息展示内容
		// 通过传参获取分页
		$page = intval(I('get.page' , 1));
		$count = $this->detailsM->where(['first'=>0, 'tid'=>$id , 'isdel'=>0])->count();
		$pageCount = ceil($count / 6);
		if ($pageCount == 0) {
			$pageCount = 1;
		}
		if ($page <= 0 || $page > $pageCount) {
			$this->error('当前页面不存在，正在返回……' , '/home/single/single?sid=' . $id , 5);
			exit;
		}

		// 回帖与用户两表联查
		$reply = $this->detailsM->join('blog_user ON blog_details.authorid = blog_user.uid')->where(['blog_details.first'=>0, 'blog_details.tid'=>$id , 'blog_details.isdel'=>0])->order('blog_details.id desc')->page($page . ' , 6')->select();
		$this->assign('reply' , $reply);

		// 生成分页用的链接
		$pages['first'] = '/home/single/single?sid=' . $id . '&page=1';
		$pages['end'] = '/home/single/single?sid=' . $id . '&page=' . $pageCount;
		if ($page == 1) {
			$pages['prev'] = '/home/single/single?sid=' . $id . '&page=1';
		} else {
			$pages['prev'] = '/home/single/single?sid=' . $id . '&page=' . ($page - 1);
		}
		if ($page == $pageCount) {
			$pages['next'] = '/home/single/single?sid=' . $id . '&page=' . $pageCount;
		} else {
			$pages['next'] = '/home/single/single?sid=' . $id . '&page=' . ($page + 1);
		}
		$this->assign('pages' , $pages);

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