<?php
	class OndoAction extends Action{
		protected $Aria2;
		public function __construct(){
			parent::__construct();
			include "Aria2.php";
			$this->Aria2=new Aria2('http://127.0.0.1:6800/jsonrpc');	
		}
		protected function add_aria2($magnet,$name){//下载模块
			$dir=$this->dir_name($magnet);
			D('Pre')->add_one($magnet,$dir,$name);
			$this->Aria2->addUri(array($magnet),array('dir'=>$dir));
		}
		protected function jugg_aria2($magnet){//判断是否已经下载
			$jugg=D('Pre')->jugg($magnet);
			return $jugg;
		}
		protected function dir_name($magnet){//生成下载地址
			return DIR."/download/".md5($magnet);
		}
		protected function setinfo($inf){
			$info=$inf['result'];
			foreach ($info as $key => $value) {
				if($value['totalLength']>10*pow(2, 30)){
					$this->Aria2->remove($value['gid']);//超过10GB任务删除
					$value['status']="REMOVE";
				}
				D('Ondo')->update_info($value);//更新信息
			}
		}
		protected function ex($name){//返回拓展名
			$exa=explode(".", $name);
			return array_pop($exa);
		}
		protected function is_mp4($name){
			$ex=$this->ex($name);
			$ex=strtoupper($ex);
			if($ex=="MP4"){
				return true;
			}else{
				return false;
			}
		}
		protected function open_dir($dir,$oid){//当下载为100% 时候才进行 【不需要移动】NEW
			if(is_dir($dir)){
				$odir=opendir($dir);
				$i=1;
				while (($file=readdir($odir))!==false) {
					if($file=="."||$file==".."){
						continue;
					}
					if(is_dir($dir."/".$file)){
						$re=rename($dir."/".$file, $dir."/".$i);
						if($re){
							$this->open_dir($dir."/".$i,$oid);
							$i++;
						}else{
							$this->open_dir($dir."/".$file,$oid);
						}
					}
					if($this->is_mp4($file)){//MP4回调 $dir."/".$file;
						$re=rename($dir."/".$file, $dir."/".$i.".mp4");
						if($re){
							$this->video_add_one($dir."/".$i.".mp4",$oid);//该数据库写入
							$i++;
						}else{
							$this->video_add_one($dir."/".$file,$oid);//该数据库写入
						}
					}
				}
			}
		}
		protected function video_add_one($file,$oid){
			$add['click']=0;
			$vid=M('Video')->add($add);
			$info['dir']=$file;
			$info['or_name']=$value;
			$dir=D('Ondo')->oid_dir($oid);
			$info['name']=D('Pre')->dir_name($dir);
			$info['oid']=$oid;
			$info['magnet']=D('Pre')->dir_magnet($dir);
			$info['img']="a.jpg";//先暂时使用这个
			D('Video')->add_one($info,$vid);//video表添加
		}



		protected function dir_reader($dir,$oid){//当下载为100% 时候才进行 【需要移动】old
			$dir_array=scandir($dir);
			foreach ($dir_array as $key => $value) {
				$is_mp4=$this->is_mp4($value);
				if($is_mp4){
					$add['click']=0;
					$vid=M('Video')->add($add);
					$name=$vid.".mp4";
					$new_mp4=DIR."/video/".$name;
					copy($dir."/".$value,$new_mp4);
					$info['dir']=$new_mp4;
					$info['or_name']=$value;
					$info['name']=D('Pre')->dir_name($dir);
					$info['oid']=$oid;
					$info['magnet']=D('Pre')->dir_magnet($dir);
					$info['img']="a.jpg";//先暂时使用这个
					D('Video')->add_one($info,$vid);//video表添加
				}
			}
		}
		protected function video_add($oid){
			$oinfo=D('Ondo')->find($oid);
			if(!$oinfo['video']){
				$this->dir_reader($oinfo['dir'],$oid); //old
				//$this->open_dir($oinfo['dir'],$oid);//NEW
				$oinfo['video']=1;
				D('Ondo')->save($oinfo);
			}
		}
		protected function remove_aria2($gid){
			$this->Aria2->remove($gid);
		}
		protected function add_one($magnet,$name){//下载入口
			$is_magnet=$this->jugg_aria2($magnet);//若已经存在则不下载
			if(!$is_magnet){
				$this->add_aria2($magnet,$name);
			}
		}
		protected function auto_download($info){//获取信息
			$num=D('Control')->count();
			if($num>=1){
					if(!$this->admin_control($info['fname'])){//匹配到管理员信息时
					return false ;
				}
			}
			if($info['type']=="美剧"){//该判断可以取消
				$t=trim($info['size']);
				$mb=substr($t, -2);
				$size=substr($t, 0,strlen($t)-2);
				if($mb=="MB"&&($t>20&&$t<500)){//20MB到500MB的下载
					$this->add_one($info['magnet'],$info['fname']);
				}
			}

		}
		protected function sxp(){//输入采集页面
    		include_once 'phpQuery/phpQuery.php';
			//phpQuery::newDocumentFile("你要采集分析的网页"); 
			/*
				$info中只要提交3个值
				$info['type']: 类型 取自你采集的页面中，用于初步判断，如果你采集的页面没有的话请将133行改中if判断改成true。
				$info['magnet']: 下载地址
				$info['name']: 下载文件名

			*/
			$this->auto_download($info);
			}
    	}
	protected function admin_control($info){//管理员规则控制
    		$control_array=D('Control')->select();
    		foreach ($control_array as $key => $value) {//$value['control']
    			if($this->tag_match($value['control'],$info)){
    				return true;//匹配到跳出返回1
    			}	
    		}
    	}
    	protected function tag_match($control,$info){//管理员 多标签 控制下载
   		$control=trim($control);
    		$tag=preg_replace('/\s+/', ' ', $control);
			$tag_array=explode(" ", $tag);
			foreach ($tag_array as $key => $value) {
				$pg="/".$value."/i";
				if(!preg_match($pg, $info)){
    				return false;
    				}
				return true;
			}

    	}

    	protected function ffmpeg_jpg($video,$time,$image){
    		$exec="/usr/bin/avconv -ss ".$time." -i ".$video." -t 0.01 -f image2 -y ".$image;
    		@exec($exec);
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

    	}
    	protected function image($video,$vid){
    		$image_array="";
    		for ($i=1; $i <5 ; $i++) { 
    			$time=$i*60;
    			$r=mt_rand(1,999);
    			$rd="_".$r."_";
    			$image=DIR."/image/".$vid.$rd.$i.".jpg";
    			$this->ffmpeg_jpg($video,$time,$image);	
    			$image_array[]="/image/".$vid.$rd.$i.".jpg";
    		}
    		$this->image_sql($image_array,$vid);//写入数据库
    	}
    	function curl_image(){//获取截图
    		$info=D('Video')->not_image();//count 5
    		foreach ($info as $key => $value) {
    			$this->image($value['dir'],$value['vid']);
    		}

    	}
    	function spider(){//采集定时下载
			$this->sxp(1);
		}
		function curl(){ //外部定时访问
    			$active=$this->Aria2->tellActive();
				$wait=$this->Aria2->tellWaiting(-1,50);
				$stop=$this->Aria2->tellStopped(-1,50);
				$this->setinfo($stop);
				$this->setinfo($wait);
				$this->setinfo($active);
		}
		function curl_video(){//视屏转换
			$where['precent']=1;
			$where['video']=0;
			$info=D('Ondo')->where($where)->select();

			foreach ($info as $key => $value) {
				$this->video_add($value['oid']);
				$this->remove_aria2($value['gid']);
			}
		}
		function union(){//需要调大php运行时间
			$this->spide();
			$this->curl();
			$this->curl_video();
			$this->curl_image();

		}

	}
