<?php
namespace app\hire\controller;

use app\common\controller\BaseController;

class IndexController extends BaseController
{
   
    public function home() {
       
        return $this->fetch();
    }

    public function handle() {
        $input = input('post.');
        
       // var_dump($input);
         $func = $input['func'];
        // var_dump($func);
       // $idn = $input['idn'];
        //$site = $input['site'];
        //$empType = $input['empType'];
        //$source = $input['source'];
        //return json_encode("N");
         return $this->$func($input);
         //return "N";
    }

    

    private function CheckIdn($input) {
        return json('N');
    }

    private function MaintainPwd($input) {
        return json('successfully');
    }

    private function LogInByIdnAndPwd($input) {
        return json('successfully');
    }

    private function PutEmpBasicInfo($input) {
        //var_dump($input);
        return json('successfully');
    }
    
    private function PutHighestEduLevel($input) {
        return json('successfully');
    }

    private function PutFamily($input) {
        return json('successfully');
    }
    
    public function resource() {
        $input = input('get.');
        $func = $input['func'];
        return $this->$func($input);
    }

    private function GetCountry($input) {
        $province = model("Province")->GetProvince();
        return json($province);
    }

    private function GetCity($input) {
        $country = $input["country"];
        $province = model("Province")->GetCity($country);
        return json($province);
    }

    private function GetDressSize($input) {
        return '{"XS":"XS","XL":"XL","S":"S","M":"M","L":"L","4XL":"4XL","3XL":"3XL","2XL":"2XL"}';
    }

    private function GetShoesSize($input) {
        return '{"46":"46","45":"45","44":"44","43":"43","42":"42","41":"41","40":"40","39":"39","38":"38","37":"37","36":"36","35":"35","34":"34"}';
    }

    private function GetNation($input) {
        return '{"AC":"阿昌族 ","BA":"白族","BL":"布朗族 ","BN":"保安族 ","BY":"布依族 ","CS":"朝鲜族 ","DA":"傣族","DE":"德族","DO":"侗族","DR":"独龙族 ","DU":"达翰尔族","DX":"东乡族 ","EW":"鄂温克族","GI":"京族","GL":"仡佬族 ","GS":"高山族 ","HA":"汉族","HU":"回族","HZ":"赫哲族 ","JN":"基诺族 ","JP":"景颇族 ","KG":"柯尔克孜族 ","KZ":"哈萨克族","LB":"珞巴族 ","LH":"拉祜族 ","LI":"黎族","LS":"傈僳族 ","MA":"满族","MB":"门巴族 ","MG":"蒙古族 ","MH":"苗族","ML":"仫佬族 ","MN":"毛南族 ","NU":"怒族","NX":"纳西族 ","OR":"鄂伦春族","PM":"普米族 ","QI":"羌族","RS":"俄罗斯族","SH":"畲族","SL":"萨拉族 ","SU":"水族","TA":"塔吉克族","TJ":"土家族 ","TT":"塔塔尔族","TU":"土族","UG":"维吾尔族","UZ":"乌孜别克族 ","VA":"佤族","XB":"锡伯族 ","YA":"瑶族","YG":"裕固族 ","YI":"彝族","ZA":"藏族","ZH":"壮族"}';
    }
}
