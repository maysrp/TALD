<?php
	class PreModel extends Model{
		function add_one($magnet,$dir,$name){
			$add['time']=time();
			$add['magnet']=trim($magnet);
			$add['dir']=$dir;
			$add['name']=$name;
			$this->add($add);
		}
		function jugg($magnet){
			$where['magnet']=$magnet;
			$jugg=$this->where($where)->select();
			if($jugg){
				return true;
			}else{
				return false;
			}
		}
		function dir_info($dir){
			$where['dir']=$dir;
			$info=$this->where($where)->select();
			return $info;
		}
		function dir_name($dir){
			$info=$this->dir_info($dir);
			return $info['0']['name'];
		}
		function dir_magnet($dir){
			$info=$this->dir_info($dir);
			return $info['0']['magnet'];
		}
	}