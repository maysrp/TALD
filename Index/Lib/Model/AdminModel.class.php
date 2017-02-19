<?php
	class AdminModel extends Model{
		function jugg($user,$pass){
			$where['user']=$user;
			$info=$this->where($where)->select();
			$adm=$info['0'];
			$pre=md5(md5($pass).$adm['salt']);
			if($pre==$adm['password']){
				return true;
			}else{
				return false;
			}

		}
		function jugg_info($user,$pass){
			$where['user']=$user;
			$info=$this->where($where)->select();
			$adm=$info['0'];
			$pre=md5(md5($pass).$adm['salt']);
			if($pre==$adm['password']){
				return $info[0];
			}else{
				return false;
			}

		}
		function salt(){
			return mt_rand(100000,999999);
		}
		function change($user,$old,$new){
			$my=$this->jugg_info($user,$old);
			if($my){
				$my['salt']=$this->salt();
				$my['password']=md5(md5($new).$my['salt']);
				if($this->save($my)){
					$re['con']="修改成功";
					$re['status']=true;
				}else{
					$re['status']=false;
					$re['con']="未知原因";
				}
			}else{
				$re['status']=false;
				$re['con']="原密码错误";
			}
			return $re;

		}
	}