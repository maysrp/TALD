## NYANYA 

###环境
* Linux
* PHP >5.3 PHP<=5.6 [PHP 请在php.ini中开启scandir exec函数]
* Mysql
* Aria2 
* Livav 视频截图 [若使用FFMPEG请将OndoAction.class.php 中的 avconv 换成 ffmpeg]
* Apache [Nginx请配置 pathinfo ]



###定时进程

#### 定时进程详请

/Index/Lib/Action/OndoAction.class.php

Ondo下面公共方法

* spider 采集
* curl 获取下载状况
* curl_video 更新发布视频
* curl_image 获取截图
* union 联合上面全部 [需要耗时较多，PHP建议运行时间调到600s]


#### 定时进程配置
```
crontab -e
```

```	    
*/10 * * * * curl http://your_domain/index.php/Ondo/spider
*/10 * * * * curl http://your_domain/index.php/Ondo/curl
*/10 * * * * curl http://your_domain/index.php/Ondo/curl_video
*/10 * * * * curl http://your_domain/index.php/Ondo/curl_image
```	    

或者简易版本

```
*/10 * * * * curl http://your_domain/index.php/Ondo/union
```


### 权限
需要777权限的文件夹
* /image
* /video #若选择NEW，则不需要该文件夹 OndoAction.class.php 里面 111行 和112行 选择其中一种，不要的那个注释掉
* /download
* /Index/Runtime

### 数据库
/Index/Conf/config.php

danmu.sql 导入你的数据库中

###后台



http://your_domain/index.php/Admin/login

后台文件 /Index/Lib/Action/AdminAction.class.php


默认: admin 233

#### 验证登入

密码 :: JavaScript md5() ->服务器上 PHP::md5(md5($pass).$salt)

#### 管理视频

进入后台 管理视频时候请注意了 如果采用old 方法时删除视频不带删除 源视频，即/download下的视频将会保持。

### 采集
/Index/Lib/Action/OndoAction.class.php

sxp()方法中
sxp 使用PHPQuery分析网页，取出其中的下载信息$info。

$info中只要提交3个值
* $info['type']: 类型 取自你采集的页面中，用于初步判断，如果你采集的页面没有的话请将133行改中if判断改成true。
* $info['magnet']: 下载地址
* $info['name']: 下载文件名
递交给 auto_download($info) 即可下载 。

spider() 用于调用sxp();



## 感谢
[Thinkphp 3.1.3] (http://www.thinkphp.cn/) 
[Php-aria2] (https://github.com/shiny/php-aria2)
[Dplayer] (https://github.com/DIYgod/DPlayer)
[PHPQuery] (https://github.com/TobiaszCudnik/phpquery)
