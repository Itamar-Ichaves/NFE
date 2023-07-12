<?php
namespace app\models\service;

use stdClass;

class IpiService{ 
    public static function calculoIpi($nfe, $vBC, $tributacao){
        $std                = new \stdClass();
        $std->item          = 1; 

        $pIPI               = $tributacao->pIPI; 
        $std->CST 		    = $tributacao->cstIPI 	;        
        $std->CNPJProd 	    = $tributacao->CNPJProd ;
        $std->cSelo 		= $tributacao->cSelo 	;
        $std->qSelo 		= $tributacao->qSelo 	;
        $std->cEnq 		    = $tributacao->cEnq 	;   
             
        if($tributacao->tipo_calc_ipi=="1"){            
            $std->pIPI     = $pIPI 	;
            $std->vBC      = $vBC                        ;
            $std->vIPI     = $std->vBC * ($std->pIPI/100)     ;
           
        }else if($tributacao->tipo_calc_ipi=="2"){
            $std->qUnid 	= 1	          ;
            $std->vUnid		= $tributacao->vUnidIPI 	      ; 
            $std->vIPI      = $std->vUnid * $std->qUnid ;            
        }  

        $nfe->tagIPI($std);        
    }   

}