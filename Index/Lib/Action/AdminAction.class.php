<?php
	class AdminAction extends Action{
		public function __construct(){
			parent::__construct();
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
	}