<?php
namespace app\models\service;

use stdClass;

class IcmsService{ 
    public static function calculoIcms($nfe, $vBC, $tributacao){          
        $produto                = DadosService::produto();
        $emitente               = DadosService::emitente();
        $std                    = new stdClass();
        $std->item              = 1; 
        $std->orig              = $produto->origem;
        
        

        if($tributacao->cstICMS=="00"){                
            $std->modBC    = $tributacao->modBC;
            $std->vBC       = $vBC  ;            
            $std->pICMS    = $tributacao->pICMS;
            $std->vICMS    = $std->vBC * ($std->pICMS / 100);

        }else if($tributacao->cstICMS=="10"){           
            $std->vBC      = $vBC  ;
            $std->modBC    = $tributacao->modBC;
            $std->pICMS    = $tributacao->pICMS;
            $std->vICMS    = $std->vBC * ($std->pICMS / 100); 
               
            //Cálculo ST
            $aliquota_intra = $tributacao->iva_pIcmsIntra; 
            $pRedBCST      = $tributacao->iva_pRedBCST;
            
            $std->modBCST  = $tributacao->iva_modBCST;
            $std->pMVAST   = $tributacao->iva_pMVAST;         
            $std->pICMSST  = $aliquota_intra;

            //Base do ICMS ST
            $std->vBCST   = $vBC * (1 + ($std->pMVAST/100));      
            $icmsst        = $std->vBCST * $aliquota_intra * 0.01;
            $vICMSST       = $icmsst - $std->vICMS ;        
            $std->vICMSST =($vICMSST<=0) ? 0 : $vICMSST;


        }else if($tributacao->cstICMS=="20"){
            $std->modBC    = $tributacao->modBC;
            $std->vBC      = $vBC  ;
            $std->pICMS    = $tributacao->pICMS;
            
            $std->pRedBC   = $tributacao->pRedBC  ;   
            $base_reduzida = $std->vBC - ($std->pRedBC/100 * $std->vBC);
            $std->vBC      = $base_reduzida ;
            $std->vICMS    = $base_reduzida * ($std->pICMS / 100);
            
            $std->vICMSDeson   = $vBC * ($std->pICMS / 100);
            $std->motDesICMS   = $tributacao->motDesICMS;         
        
        }else if($tributacao->cstICMS=="30"){         
            $modBC    = $tributacao->modBC;
            $pICMS    = $tributacao->pICMS;
            $vICMS    = $vBC * ($pICMS / 100); 
          
            //Cálculo ST
            $aliquota_intra = $tributacao->iva_pIcmsIntra; 
            $pRedBCST      = $tributacao->iva_pRedBCST;
            
            $std->modBCST  = $tributacao->iva_modBCST;
            $std->pMVAST   = $tributacao->iva_pMVAST;         
            $std->pICMSST  = $aliquota_intra;

          
            $std->vBCST   = $vBC * (1 + ($std->pMVAST/100));      
            $icmsst        = $std->vBCST * $aliquota_intra * 0.01;
            $vICMSST       = $icmsst - $vICMS ;        
            $std->vICMSST =($vICMSST<=0) ? 0 : $vICMSST;    

            
            $std->vICMSDeson   = $vBC * ($pICMS / 100);
            $std->motDesICMS   = $tributacao->motDesICMS;            
            
        }else if($tributacao->cstICMS=="40" || $tributacao->cstICMS=="41" || $tributacao->cstICMS=="50" ){ 
            $pICMS              = $tributacao->pICMS;
            $std->vICMSDeson   = $vBC * ($pICMS / 100);
            $std->motDesICMS   = $tributacao->motDesICMS;
            
        }else if($tributacao->cstICMS=="51"){            
            $std->modBC    = $tributacao->modBC;
            $std->vBC      = $vBC  ;
            $std->pICMS    = $tributacao->pICMS;
            $std->pDif     = $tributacao->pDif ;
                      
            $std->vICMSOp   = $std->vBC * ($std->pICMS / 100);
            $std->vICMSDif  = $std->vICMSOp * ($std->pDif / 100);
            $std->vICMS     = $std->vICMSOp  - $std->vICMSDif;             
        }else if($tributacao->cstICMS=="60" ){
            $std->pICMS      = $tributacao->pICMS;
            $std->vBCSTRet     = 0;
            $std->vICMSTRet    = 0;
            $std->vICMSDeson   = $vBC * ($std->pICMS / 100);
            $std->motDesICMS   = $tributacao->motDesICMS;

        }else if($tributacao->cstICMS=="70"){
            $std->modBC     = $tributacao->modBC;
            $std->vBC       = $vBC  ;
            $std->pICMS     = $tributacao->pICMS;
            
            $std->vICMSDeson   = $vBC * ($std->pICMS  / 100);
            $std->motDesICMS   = $tributacao->motDesICMS;
            
            //calculo Redução
            $std->pRedBC   = $tributacao->pRedBC  ;   
            $base_reduzida = $std->vBC - ($std->pRedBC/100 * $std->vBC);
            $std->vBC      = $base_reduzida ;
            $std->vICMS    = $base_reduzida * ($std->pICMS / 100);                
            

            //Cálculo ST
            $aliquota_intra = $tributacao->iva_pIcmsIntra; 
            $pRedBCST      = $tributacao->iva_pRedBCST;

            $std->modBCST  = $tributacao->iva_modBCST;
            $std->pMVAST   = $tributacao->iva_pMVAST;         
            $std->pICMSST  = $aliquota_intra;
            
            $std->vBCST   = $vBC * (1 + ($std->pMVAST/100));        
            if($pRedBCST){
                $base_reduzida  = $std->vBCST - ($pRedBCST/100 * $std->vBCST) ;               
                $std->pRedBCST = $pRedBCST;
                $std->vBCST    = $base_reduzida;                
            }       
        
            $icmsst        = $std->vBCST * $aliquota_intra * 0.01;
            $vICMSST       = $icmsst - $std->vICMS ;        
            $std->vICMSST =($vICMSST<=0) ? 0 : $vICMSST; 

        } elseif($tributacao->cstICMS=="90"){
            $std->vBC      = $vBC  ;
            $std->modBC    = $tributacao->modBC;
            $std->modBCST  = $tributacao->modBCST;
            $std->pRedBC   = 0;
            $std->pICMS    = 0;
            $std->vICMS    = 0;
            $std->vBCST    = 0;
            $std->pICMSST  = 0;
            $std->vICMSST  = 0;   
        
        }elseif($tributacao->cstICMS=="101"){ 
            $std->pCredSN      = ($emitente->pCredSN) ? $emitente->pCredSN: 0;
            $std->vCredICMSSN  = $std->vProd * ($std->pCredSN / 100);
           
            
        }else if($tributacao->cstICMS=="201"){
            $std->pCredSN   = ($emitente->pCredSN) ? $emitente->pCredSN: 0;
            $std->destaca_icms = 'N';
            $std->vBC       = $vBC  ;
            $std->modBC     = $tributacao->modBC;
            $std->pICMS     = $tributacao->pICMS;
            $std->vICMS     = $std->vBC * ($std->pICMS / 100);
            $std->vCredICMSSN  = $produto->preco * ($std->pCredSN / 100);
            
            //Cálculo ST
            $aliquota_intra = $tributacao->iva_pIcmsIntra; 
            $pRedBCST      = $tributacao->iva_pRedBCST;

            $std->modBCST  = $tributacao->iva_modBCST;
            $std->pMVAST   = $tributacao->iva_pMVAST;         
            $std->pICMSST  = $aliquota_intra;

            //Base do ICMS ST
            $std->vBCST   = $vBC * (1 + ($std->pMVAST/100));      
            $icmsst        = $std->vBCST * $aliquota_intra * 0.01;
            $vICMSST       = $icmsst - $std->vICMS ;        
            $std->vICMSST =($vICMSST<=0) ? 0 : $vICMSST;  

        }else if($tributacao->cstICMS=="202" || $tributacao->cstICMS=="203" ){
            $std->vBC       = $vBC  ;
            $std->modBC     = $tributacao->modBC;
            $std->pICMS     = $tributacao->pICMS;
            $std->vCredICMSSN  = $produto->preco * ($std->pCredSN / 100);
			
            //Cálculo ST
            $aliquota_intra = $tributacao->iva_pIcmsIntra; 
            $pRedBCST      = $tributacao->iva_pRedBCST;

            $std->modBCST  = $tributacao->iva_modBCST;
            $std->pMVAST   = $tributacao->iva_pMVAST;         
            $std->pICMSST  = $aliquota_intra;

            //Base do ICMS ST
            $std->vBCST   = $vBC * (1 + ($std->pMVAST/100));      
            $icmsst        = $std->vBCST * $aliquota_intra * 0.01;
            $vICMSST       = $icmsst - $std->vICMS ;        
            $std->vICMSST =($vICMSST<=0) ? 0 : $vICMSST; 
			
        }else if($tributacao->cstICMS=="500"  ){
            $std->pICMS        = null;
            $std->vICMS        = null;
            $std->vBCSTRet     = 0;
            $std->vICMSTRet    = 0;		
			
        }else if($tributacao->cstICMS=="900"){  
            $std->modBC     = $tributacao->modBC;
            $std->modBCST   = $tributacao->modBCST;
            
            $std->vBC       = $vBC ;
            $std->vBCFCPST  = $vBC;
            $std->pICMS     = $tributacao->pICMS;

            //calculo Redução
            if($tributacao->pRedBC){
                $std->pRedBC   = $tributacao->pRedBC  ;   
                $base_reduzida = $std->vBC - ($std->pRedBC/100 * $std->vBC);
                $std->vBC      = $base_reduzida ;
                $std->vICMS    = $base_reduzida * ($std->pICMS / 100);  
            }else{
                $std->vICMS    = $std->vBC * ($std->pICMS / 100);
            }
            
                        
            //Substituição tributária
            $aliquota_intra = $tributacao->iva_pIcmsIntra; 
            $pRedBCST      = $tributacao->iva_pRedBCST;
            
            $std->modBCST  = $tributacao->iva_modBCST;
            $std->pMVAST   = $tributacao->iva_pMVAST;         
            $std->pICMSST  = $aliquota_intra;

            //Base do ICMS ST
            $std->vBCST   = $vBC * (1 + ($std->pMVAST/100));      
            $icmsst        = $std->vBCST * $aliquota_intra * 0.01;
            $vICMSST       = $icmsst - $std->vICMS ;        
            $std->vICMSST =($vICMSST<=0) ? 0 : $vICMSST;
            
            //Crédito SN
                          
            $std->pCredSN      = ($emitente->pCredSN) ? $emitente->pCredSN: 0;
            $std->vCredICMSSN  = $std->vProd * ($std->pCredSN / 100);                       
        }
  
        if (intval($tributacao->cstICMS) < 100){
            $std->CST               = $tributacao->cstICMS;
            $nfe->tagICMS($std);
        }else{
            $std->CSOSN             = $tributacao->cstICMS;
            $nfe->tagICMSSN($std);   
        }

        return $std->vICMSST;
            
     }   



}