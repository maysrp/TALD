<?php
	class VideoModel extends Model{
		function add_one($info,$vid){
			//$add['name']=$info['name'];
			$add['or_name']=$info['or_name'];
			$info['name']=str_replace('GB', '简体', $info['name']);
			$info['name']=str_replace('BIG5', '繁体', $info['name']);
			$info['name']=str_replace('MP4', '', $info['name']);
			$info['name']=str_replace('[]', '', $info['name']);
			$add['name']=$info['name'];
			$add['dir']=$info['dir'];
			$add['time']=time();
			$add['oid']=$info['oid'];
			$add['img']=$info['img'];
			$add['magnet']=$info['magnet'];
			$add['vid']=$vid;
			$this->save($add);
		}
		function not_image(){//返回没有截图的5个视频
			$where['img_array']=array('between',array('','null'));
			return $this->where($where)->limit(5)->select();
		}

	}