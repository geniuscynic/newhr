<?php
namespace app\admin\controller;

use app\common\controller\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

//require_once '/verdor/HTMLPurifier/HTMLPurifier.auto.php';

class ResumeController extends BaseController
{

    public function List() {
       
        $resume = model("Resume")->getResumeList();

        $this->assign('resumeList', $resume);

        //dump($resume);
        return $this->fetch();
    }

   public function exportExcel()
   {
       $input = input("post.");
       $data = explode(',', $input['data']);
       //dump($data);
       
        $resumeList = model("Resume")->searchResume($data);

        //$html = __DIR__ . '/../view/resume/list.html';
        //$objReader = IOFactory::createReader('Html');
        //$objPHPExcel = $objReader->load($html);

        $spreadsheet = new Spreadsheet();

        // $spreadsheet->getDefaultStyle()
        //             ->getNumberFormat()
        //             ->setFormatCode(NumberFormat::FORMAT_TEXT);

        //$conditionalStyles = $spreadsheet->getActiveSheet()->getDefaultStyle()->getConditionalStyles();

        $dataArray = [[
            '简历ID',
            '姓名',
            '性别',
            '电话',
            '身份证号',
            '出生日期',
            '民族',
            '最高学历',
            '政治面貌',
            '工作年限',
            '现居地址',
            '求职状态',
            '到岗时间',
            '从事行业',
            '期望工作性质',
            '期望岗位(大类)',
            '期望岗位(小类)',
            '期望岗位(细分)',
            '期望薪资',
            '期望工作地点',
            '紧急联络人',
            '紧急联系电话',
            '个人证书技能',
            '最后更新时间'
        ]];

        foreach($resumeList as $resume) {

            foreach($resume['skills'] as $skill) {
                $resume['skill'] .= $skill['name'] . ",";
            }
            $resume['skill'] = trim($resume['skill'], ",");

            foreach($resume['quarters'] as $quarter) {
                $resume['quarter1'] = $quarter['name1'];
                $resume['quarter2'] = $quarter['name2'];
                $resume['quarter3'] .= $quarter['name3'] . ",";
            }

            $resume['quarter3'] = trim($resume['quarter3'], ",");
            unset($resume['createDate']);
            unset($resume['quarters']);
            unset($resume['skills']);
            unset($resume['workExperiences']);
            unset($resume['trains']);
            unset($resume['families']);

            $resume['cardno'] = "'" . $resume['cardno'] ;
            $dataArray[] = $resume;
        }

       

        // //dump($dataArray);
        $spreadsheet->getActiveSheet()->fromArray($dataArray, null, 'A1');

        $spreadsheet->getActiveSheet()->getStyle('A1:Y1')->getFont()->setBold(true);
        $spreadsheet->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="01simple.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        
        exit;
   }

   public function detail() {
   
    $resume = model("Resume")->getResumeById(input("id"));
    $this->assign('resume', $resume->getResultValue());
    return $this->fetch();
   }
}
