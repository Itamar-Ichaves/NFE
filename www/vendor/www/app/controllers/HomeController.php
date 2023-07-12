<?php
namespace app\controllers;
use app\core\Controller;
use app\models\service\DadosService;
use app\models\service\IcmsService;
use app\models\service\IpiService;
use app\models\service\PisCofinsService;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;
use NFePHP\NFe\Make;
use NFePHP\NFe\Tools;
use stdClass;

class HomeController extends Controller{
    public function index(){
        $dados["view"]   = "home";
	    $this->load("template", $dados);
    }  

  
}
