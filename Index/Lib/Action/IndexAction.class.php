<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
    public function index(){
		$this->display();
    }
    public function video(){
    	$vid=$this->_get('vid');
    	$info=M('Video')->find($vid);
    	if($info){
    		$where['vid']=$vid;
    		M('video')->where($where)->setInc("click");
    		$ip=$_SERVER['REMOTE_ADDR'];
    		$time=time();
    		$token=base64_encode($ip.":".$time.":".$vid);
            if(strlen($info['img_array'])>10){
                $img_array=json_decode($info['img_array']);
                $akey=array_rand($img_array);
                $info['img']=$img_array[$akey];
            }else{
                $info['img']="/a.jpg";
            }
            $info['dir']=str_replace(DIR, "", $info['dir']);
    		$this->assign("token",$token);
    		$this->assign("info",$info);
    		$this->display();
    	}else{
    		$this->error("无该视频");
    	}

    }
    public function video_load(){//加载
    	$page=$this->_get('page');//10条数据
    	$page=isset($page)?(int)$page:1;//初始化
    	$info=D('Video')->limit(($page-1)*10,$page*10)->order("vid desc")->select();
    	$this->ajaxReturn($info);//AJAX
    }
    public function search(){
    	$search=$this->_get('search');
    	$info=$this->sea($search);
    	import('ORG.Util.Page');
    	$count=@count($info);
    	$Page=new Page($count,20);
    	$Page->setConfig('header',"条信息");
    	if($_GET['p']<1){
          	$_GET['p']=1;
        }else{
            $_GET['p']=(int)$_GET['p'];//
        }
        $list=array_slice($info, 20*($_GET['p']-1),20);
        $show=$Page->show();
		$this->assign("page",$show);
    	$this->assign("info",$list);
    	$this->display();
    }
    protected function sea($info){
    	$info=trim($info);
    	$where['name']=array('like','%'.$info.'%');
    	return D('video')->where($where)->select();
    }
}