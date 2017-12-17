<?php
/**
 * Created by PhpStorm.
 * User: 黑小马
 * Date: 2017/11/18
 * Time: 18:29
 */
include "BTFOX.php";
$hash = "A3D756844746E919208F8EDA67406E8EF11097F8";
$bt=new BTFOX($hash);
//获取信息
echo "\n".$bt->getMsg();
//获取视频地址
echo "\n".$bt->getPlay();
