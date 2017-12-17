<?php
/**
 * Created by PhpStorm.
 * User: 黑小马
 * Date: 2017/11/18
 * Time: 16:59
 */
class BTFOX
{
    private $hash;
    private $purl;
    private $data;
    private $cookies;
    private $user="872125493";
    private $pwd="872125493";
    private $sign;
    private $down_url;
    function BTFOX($hash)
    {
        $this->hash=$hash;
        $this->login();
    }
    private function decode($s)
    {
        $str = '';
        $temp = array();
        for ($i = 0; $i < strlen($s) / 2; $i++) {
            $temp[] = hexdec(substr($s, $i * 2, 2));
        }

        foreach ($temp as $ch) {
            $str .= chr($ch);
        }
        return $str;
    }

   private function api_check_time()
    {
        list($s1, $s2) = explode(' ', microtime());
        $time = sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
        return substr($time, 0, 10);
    }

    private function api_check($check_time)
    {
        return md5(sha1($check_time . "BTFox20170001"));
    }

    private function api_check_strkey()
    {
        return md5("BTFox20170001" .$this->getdates());
    }

    private function getdates()
    {
        $arraydata = getdate();
        return $arraydata["mon"] . $arraydata["mday"] . $arraydata["hours"];
    }
    public function getMsg(){
        $checkTime=$this->api_check_time();
        $apiCheck=$this->api_check($checkTime);
        $apiCheckStrkey=$this->api_check_strkey();
        $url = "http://api.btfox.cc/?" . "do=parse_magnet_info&" . "hash=".$this->hash
            . "&login_name=872125493" . "&login_sign=".$this->sign. "&api_check_time="
            . $checkTime . "&api_check=" . $apiCheck . "&api_check_strkey=" . $apiCheckStrkey;
        $res= $this->decode(file_get_contents($url));
        preg_match_all("/url\":\"(.*?)\"/",$res,$regsq);
        $this->purl=$regsq[1][0];
        return $res;

    }
    public function getPlay(){
        $checkTime=$this->api_check_time();
        $apiCheck=$this->api_check($checkTime);
        $apiCheckStrkey=$this->api_check_strkey();
        $playUrl = "http://api.btfox.cc/?" . "do=parse_url&" . "url=" . $this->purl . "&" . "login_name=872125493&"
            . "login_sign=".$this->sign. "&api_check_time=" . $checkTime . "&"
            . "api_check=" . $apiCheck . "&" . "api_check_strkey=" . $apiCheckStrkey;
       // return $this->decode($this->http_post($playUrl,$this->cookies));
        $res= $this->decode(file_get_contents($playUrl));
        preg_match_all("/down_url\":\"(.*?)\"/",$res,$regsq);
        $this->down_url=$regsq[1][0];
        preg_match_all("/down_cookie\":\"(.*?)\"/",$res,$regs);
        $this->cookies=$regs[1][0];

        return "播放地址：".str_replace("\\","",$this->down_url)."播放Cookie：".$this->cookies;

    }

    private function login(){
        $checkTime=$this->api_check_time();
        $apiCheck=$this->api_check($checkTime);
        $apiCheckStrkey=$this->api_check_strkey();
        $urls="http://api.btfox.cc/?do=BTFox_User_Login&name=".$this->user."&paw=".$this->pwd."&api_check_time=".$checkTime."&api_check=".$apiCheck."&api_check_strkey=".$apiCheckStrkey;
        $res = $this->decode(file_get_contents($urls));
        preg_match_all("/sign\":\"(.*?)\"/",$res,$regsq);
        $this->sign=$regsq[1][0];
    }
}