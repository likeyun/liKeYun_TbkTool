# TbkTool
**淘宝客工具箱，方便淘宝客推广者在微信朋友圈、微信群等渠道进行推广淘口令，生成中间页用于安全推广措施。**

因为自己有好几个微信号，都是学生，所以本人做了1年淘宝客，一个月赚个两三千也是钱啊。但是微信做淘客，风险比较大，特别是朋友圈，一发淘口令就被屏蔽，做久了还会封号，毕竟微信的地盘，怎能让其他人进来分一杯羹呢？

我在朋友圈看到很多同行都这么做，他们做淘宝客的方式极其简单，看起来又不像广告，也没有微商那么讨厌。就是一段话、一个链接、一张配图，就非常简单的方式去做，我更愿意点击这个链接进去。

这样的好处就是，微信无法检测我们的朋友圈是不是发了淘口令，因为淘口令全都在链接里面。

所以自己开发一款淘口令工具，把淘口令弄到一个页面给大家复制，这样就避免了朋友圈的检测。<br/>

<img src="https://ucc.alicdn.com/pic/developer-ecology/83b32d04529f4c0bb082b2b457c8db50.jpg" width="400"/><br/>

交流、解决问题、定制、学习等可以加入我们的开发者交流群
https://t.focus-img.cn/sh740wsh/bbs/p2/5d81cbd190009054cd755445e3d4d7fe.png

# 微信扫码进群
https://t.focus-img.cn/sh740wsh/bbs/p2/5d81cbd190009054cd755445e3d4d7fe.png

# 维护日志（2022-10-19）
收到很多反馈说淘口令解析失败，经过排查是API失效，现在已经完成API的更换，更换为折淘客的接口。链接：http://www.zhetaoke.com，以上代码已经更新了最新接口，大家只需要替换以下几个php文件即可。<br/>

1、admin/Creat-Zjy-TklRead-do.php<br/>
2、admin/Peizhi.php<br/>
3、admin/ShouQuan.php<br/>
<br/>
还需要到数据库修改一个数值，登录数据库找到tbk_user这个表，然后修改tbname字段里面的值改为折淘客授权管理页面的授权账号ID（sid），页面链接是：http://www.zhetaoke.com/user/shouquan.html 例如原来你填写的是你的淘宝账号，现在要填写sid，sid是一个数值来的。还需要修改appkey为折淘客的appkey，保存就可以正常使用了。



# 当前v3.0版
1、优化UI<br/>
2、登录新增记住账号密码<br/>
3、新增自带短网址<br/>
4、可创建账号，多人使用<br/>
5、更换更好用的富文本编辑器<br/>
6、新增在微信跳转到淘宝App领券（仅限IOS系统）<br/>

# 优化后的部分截图
![tbk_1.png](https://ucc.alicdn.com/pic/developer-ecology/b46b8942ad914e47bc0a6af68489801e.png)
![tbk_2.png](https://ucc.alicdn.com/pic/developer-ecology/fc9d8a8acd2b4561a0309ea2fa72bf7c.png)
![tbk_3.png](https://ucc.alicdn.com/pic/developer-ecology/63252b1c59534df69d910baa57a71aee.png)
![tbk_4.png](https://ucc.alicdn.com/pic/developer-ecology/9f6bab2c44d64ce7a872a223eedaa022.png)
![tbk_5.png](https://ucc.alicdn.com/pic/developer-ecology/c822e0350df94b39b6af6112bfd6bab5.png)
![tbk_7.png](https://ucc.alicdn.com/pic/developer-ecology/6aba51bec2204697b18b92ac6e1f7d15.png)

# 快速安装
1、下载完整代码<br/>
2、上传到服务器<br/>
3、访问安装/install/目录<br/>
4、填写相关数值，即可完成安装！<br/>
5、进入后台，直接访问/admin/即可<br/><br/>

# 伪静态设置
使用工具箱自带的【本地短网址】，需要设置伪静态规则，具体如下：

Nginx服务器
---
```
location / {
  if (!-e $request_filename) {
    rewrite ^/(.*)$ /s/index.php?id=$1 last;
  }
}
```

Apache服务器
---
Apache服务器的伪静态规则我们已经集成在源码中。


# 安装和使用遇到问题请加入交流群
交流、解决问题、定制、学习等可以加入我们的开发者交流群
https://sc01.alicdn.com/kf/H574da7b723cd4c088b082ab93ab6eb8dV.png

# 领券页面demo
<img src="https://ucc.alicdn.com/pic/developer-ecology/2c4d835015fe44ff99caf43ba42568c2.jpg" width="250" />
