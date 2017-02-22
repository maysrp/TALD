<?php
	class AdminAction extends Action{
		public $Aria2;
		public function __construct(){
			parent::__construct();
			include_once("Aria2.php");
			$this->Aria2=new Aria2('http://127.0.0.1:6800/jsonrpc');//只有开启后才有用。
		}
		protected function auth(){
			$auth=$this->_session('auth');
			if($auth){
				return true;
			}else{
				return false;
			}
		}
		protected function auth_true(){//已经登入时候报错
			$re=$this->auth();
			if($re){
				$this->error("你已经登入",U("Admin/index"));
				return;
			}else{
			}
		}
		protected function auth_false(){//未登入时候报错
			$re=$this->auth();
			if($re){
			}else{
				$this->error("你还未登入",U("Admin/login"));
				return;
			}
		}
		function logout(){
			$_SESSION['auth']="";
			$this->success("你已经成功退出！");
		}
		function login_jugg(){
			$user=$this->_post('user');
			$passwd=$this->_post('passwd');

			$re=D('Admin')->jugg($user,$passwd);
			/*  使用PHP中定义密码
			$name="admin";
			$password="12321";
			if($passwd==md5($password)&&$user==$name){
			*/
			if($re){
				$_SESSION['auth']="admin";
				$_SESSION['user']=$user;
				$this->success("登入中....",U("Admin/index"));
			}else{
				$this->error("请的输入正确的用户名");
			}
		}
		function index(){//管理页面
			$this->auth_false();
			$con=D('Video')->count();
			$control_count=D('Control')->count();
			$info=$this->control_load();
			$this->assign("info",$info);
			$this->assign("count",$con);//总资源
			$this->assign("control",$control_count);//总规则
			$this->display();
		}

		function login(){
			$this->auth_true();
			$this->display();
		}
		function video_del(){//删除
			$this->auth_false();
			$vid=$this->_get('vid');
			$vid=(int)$vid;
			$info=D('Video')->find($vid);
			$this->del_unlink($info['dir']);	
			$type=D('Video')->delete($vid);
			$this->ajaxReturn($type);//AJAX
		}
		function video_load(){//资源
			$this->auth_false();
			$page=$this->_get('page');//10条数据
    		$page=isset($page)?(int)$page:1;//初始化
    		$info=D('Video')->limit(($page-1)*10,$page*10)->order("vid desc")->select();
    		$this->ajaxReturn($info);//AJAX
		}
		function change(){
			$this->auth_false();
			$user=$this->_session('user');
			$old=$this->_post('old');
			$new=$this->_post('new');
			$re=D('Admin')->change($user,$old,$new);
			$this->ajaxReturn($re);
		}
		
		protected function control_load(){//规则
    		$info=D('Control')->select();
    		return $info;
		}
		protected function del_unlink($file){
			unlink($file);
		}
		function control_add(){
			$this->auth_false();
			$info=$this->_post('add');
			$add['control']=$info;
			$type=D('Control')->add($add);
			$this->ajaxReturn($type);//AJAX
		}
		function control_save(){
			$this->auth_false();
			$info['cid']=$this->_post('cid');
			$info['control']=$this->_post('control');
			$type=D('Control')->save($info);
			$this->ajaxReturn($type);//AJAX
		}
		function control_del(){
			$this->auth_false();
			$cid=$this->_get('cid');
			$type=D('Control')->delete($cid);
			$this->ajaxReturn($type);//AJAX
		}
		function magnet_add(){//手动添加剂一个信息
			$this->auth_false();
			$magnet=$this->_post('magnet');
			$name=$this->_post('name');
			$re=$this->add_one($magnet,$name);
			$this->ajaxReturn($re);
		}
		function aria2_control(){
			$this->auth_false();
			$active=$this->Aria2->tellActive();
			$waiting=$this->Aria2->tellWaiting(0,100);
			$info['active']=$active['result'];
			$info['waiting']=$waiting['result'];
			$this->ajaxReturn($info);
		}
		function aria2_del(){//删除
			$this->auth_false();
			$gid=$this->_get('gid');
			$gid=trim($gid);
			$info=$this->Aria2->remove($gid);
			$this->ajaxReturn($info);
			//success: array(3) { ["id"]=> string(1) "1" ["jsonrpc"]=> string(3) "2.0" ["error"]=> array(2) { ["code"]=> int(1) ["message"]=> string(41) "GID#460118a5260319dc cannot be paused now" } }
			//error: array(3) { ["id"]=> string(1) "1" ["jsonrpc"]=> string(3) "2.0" ["result"]=> string(16) "e92d8122633f306b" }
		}
		function aria2_stop(){//停止
			$this->auth_false();
			$gid=$this->_get('gid');
			$gid=trim($gid);
			$info=$this->Aria2->pause($gid);
			$this->ajaxReturn($info);
		}
		function aria2_active(){//启动
			$this->auth_false();
			$gid=$this->_get('gid');
			$gid=trim($gid);
			$info=$this->Aria2->unpause($gid);
			$this->ajaxReturn($info);
		}
		function image_change(){
			$this->auth_false();
			$vid=$this->_get('vid');
			$re=$this->image($vid);
			$this->ajaxReturn($re);

		}
		protected function image_sql($image_array,$vid){
    		foreach ($image_array as $key => $value) {
    			if(is_file(DIR.$value)){
    				$img_array[]=$value;
    			}
    		}
    		$akey=array_rand($img_array,1);
    		$save['img']=$img_array[$akey];
    		$save['img_array']=json_encode($img_array);
    		$save['vid']=$vid;
    		D('Video')->save($save);
    		return $save['img'];

    	}
    	protected function image($vid){
    		$info=D('Video')->find($vid);
    		if(!$info){
    			$re['status']=false;
    			$re['con']="无该视频";
    			return $re;
    		}
    		$video=$info['dir'];
    		$image_array="";
    		for ($i=0; $i <10 ; $i++) { 
    			$time=$i*60;
    			$r=mt_rand(1,999);
    			$rd="_".$r."_";
    			$image=DIR."/image/".$vid.$rd.$i.".jpg";
    			$this->ffmpeg_jpg($video,$time,$image);	
    			$image_array[]="/image/".$vid.$rd.$i.".jpg";
    		}
    		$im=$this->image_sql($image_array,$vid);//写入数据库
    		$re['status']=true;
    		$re['con']=$im;
    		return $re;

    	}
    	protected function ffmpeg_jpg($video,$time,$image){
    		$exec="/usr/bin/avconv -ss ".$time." -i ".$video." -t 0.01 -f image2 -y ".$image;
    		@exec($exec);
    	}
		protected function add_one($magnet,$name){//下载入口
			$is_magnet=$this->jugg_aria2($magnet);//若已经存在则不下载
			if(!$is_magnet){
				$re=$this->add_aria2($magnet,$name);
				return $re;
			}else{
				$re['error']="该下载链接已经存在";
				return $re;
			}
		}
		protected function add_aria2($magnet,$name){//下载模块
			$dir=$this->dir_name($magnet);
			D('Pre')->add_one($magnet,$dir,$name);
			$re=$this->Aria2->addUri(array($magnet),array('dir'=>$dir));
			return $re;
		}
		protected function jugg_aria2($magnet){//判断是否已经下载
			$jugg=D('Pre')->jugg($magnet);
			return $jugg;
		}
		protected function dir_name($magnet){//生成下载地址
			return DIR."/download/".md5($magnet);
		}
	}
