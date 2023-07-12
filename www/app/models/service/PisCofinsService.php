<?php
namespace app\models\service;

use stdClass;

class PisCofinsService{ 
    public static function calculoPis($nfe, $vBC, $tributacao){
        $std                = new \stdClass();
        $std->item          = 1; 

        $pPIS               = $tributacao->pPIS; 
        $std->CST           = $tributacao->cstPIS  			   ;    
                
        if(($std->CST =='01') || ($std->CST =='02')) {
            $std->vBC       = $vBC      ;
            $std->pPIS      = $pPIS               ;
            $std->vPIS      = $std->vBC * ($std->pPIS/100);
        }else if($std->CST =='03'){
            $std->vBC          = null;
            $std->qBCProd   = $std->qCom ;
            $std->vAliqProd= $tributacao->vAliqProd_pis       ;
            $std->vPIS = $std->vAliqProd_pis  * $std->qBCProd ;
        }else if($std->CST =='99'){
            if($tributacao->pPIS){
                $std->pPIS     = $pPIS;
                $std->vBC   = $vBC      ;
                $std->vPIS     = $vBC  * ($std->pPIS/100);
            }else if($tributacao->vAliqProd_pis ){
                $std->vAliqProd = $tributacao->vAliqProd_pis;
                $std->qBCProd   = $std->qCom ;
                $std->vBC   = null;
                $std->vPIS     = $std->vAliqProd_pis  * $std->qBCProd ;
            }else {
                $std->vBC   = null;
                $std->pPIS     = null;
                $std->vPIS     = null;
            }                
        }else{
            $std->vBC = null;
            $std->vPIS = null;
        } 

        $nfe->tagPIS($std);       
    } 
    
    public static function calculoCofins($nfe, $vBC, $tributacao){
        $std                = new \stdClass();
        $std->item          = 1; 

        $pCOFINS            =  $tributacao->pCOFINS; 
        $std->CST	        = $tributacao->cstCOFINS		    ;  
        $std->vCOFINS              = null;       
        
        if(($std->CST =='01') || ($std->CST =='02')) {
            $std->pCOFINS      = $pCOFINS              ;
            $std->vBC    = $vBC     ;           
            $std->vCOFINS      = $std->vBC * ($std->pCOFINS/100);
        }else if($std->CST =='03'){
            $std->vBC        = null;
            $std->qBCProd    = $std->qCom                       ;
            $std->vAliqProd = $tributacao->vAliqProd_cofins     ;
            $std->vCOFINS          = $std->vAliqProd * $std->qBCProd;
        }else if($std->CST =='99'){
            if($tributacao->pCOFINS){
                $std->vBC  = $vBC     ;
                $std->pCOFINS    = $pCOFINS;
                $std->vCOFINS    = $vBC * ($std->pCOFINS/100);
            }else if($tributacao->vAliqProd_cofins){
                $std->vAliqProd = $tributacao->vAliqProd_cofins;
                $std->qBCProd= $std->qCom ;
                $std->vBC    = null;                
                $std->vCOFINS      = $std->vAliqProd_cofins * $std->qBCProd;
            }else{
                $std->vBC = null;
                $std->pCOFINS   = null;
                $std->vCOFINS   = null;
            }
        }else{
            $std->vBC = null;
            $std->vCOFINS  = null;            
        } 
        $nfe->tagCOFINS($std);
    } 

}