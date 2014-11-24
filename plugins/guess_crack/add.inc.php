<?php
if(!defined('IN_KKFRAME')) exit('Access Denied!');
?>
<style>@import "plugins/guess_crack/guesscrack.css"</style>
<div class="cracking-icon hidden h"><img src="./template/default/style/loading.gif?version=1.14.6.2"> 破解中...<br />  正在破解第<span name="guess_crack_bn"></span>类第<span name="guess_crack_sn"></span>关</div>
<h2>猜乐个猜破解</h2>
<p class="small_gray">当前插件版本：1.0 | 更新日期：14-01-18 | Designed By <a>@qwerty472123</a></p>
<p>猜乐个猜是获得百度成就的一个活动/小游戏,在网页 <a class="guess-info" href="http://www.baidu.com/ur/show/uhguesshome" target="_blank">http://www.baidu.com/ur/show/uhguesshome</a> 中,但手动完成所有关卡很耗时.</p>
<p>本工具用于自动完成完成猜乐个猜小游戏.qwerty472123</p>
<p class="guesscenter">类别号(1-8[普通类]或100-102[圣诞类]):<br /><input type="number" name="guess_crack_b" value="1" /><br />
关卡号(1-<span name="guess_crack_sixt">16</span>):<br /><input type="number" name="guess_crack_s" value="1" /><br /><label><input type="checkbox" name="guess_crack_j">在完成一个关卡后自动继续破解下一个关卡</label><br /><a class="btn" onclick="doguess();">破解</a></p>