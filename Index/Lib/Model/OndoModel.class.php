<?php
	class OndoModel extends Model{
		function update_info($info){//后台刷新任务
			$xxx['hash']=$info['infoHash'];
			$is_d=$this->where($xxx)->select();
			if($is_d){//hash时 才记录
				if($info['totalLength']<3*pow(2, 20)){
					return;
				}
				$where['hash']=$info['infoHash'];
				$where['status']=array('neq','complete');
				$where['_logic']='and';
					$ref['status']=$info['status'];
				$rex=$this->where($where)->select();
				$ref=$rex[0];
				if($ref){//如果数据库已经完成就不动作
					$ref['gid']=$info['gid'];
					$ref['name']=$ref['name']?$ref['name']:$info['bittorrent']['info']['name'];
					$ref['complete']=$info['completedLength'];//已经完成的
					$ref['total']=$info['totalLength'];//总任务
					$ref['speed']=$info['downloadSpeed'];
					$ref['status']=$info['status'];
					$ref['dir']=$info['dir'];
					if($info['totalLength']==$info['completedLength']){
						$ref['precent']=1;
					}else{
						$ref['precent']=round($info['completedLength']/$info['totalLength'],2);
					}
					$this->save($ref);
				}
			}else{//创建新的信息
				$add['hash']=$info['infoHash'];
				$add['gid']=$info['gid'];
				$add['name']=$info['bittorrent']['info']['name'];
				$add['complete']=$info['completedLength'];//已经完成的
				$add['total']=$info['totalLength'];//总任务
				$add['speed']=$info['downloadSpeed'];
				$add['status']=$info['status'];
				$add['dir']=$info['dir'];
				$this->add($add);
			}
		}
		function oid_dir($oid){
			$info=$this->find($oid);
			return $info['dir'];
		}
	}