<?php
namespace app\models\service;

use stdClass;

class DadosService{ 
    public static function emitente(){
        $emitente = new stdClass;
        $emitente->razao_social     = "Empresa";
        $emitente->nome_fantasia    = "Empresa";
        $emitente->ie               = "202443086";
        $emitente->iest             = null;
        $emitente->im               = null;
        $emitente->cnae             = null;
        $emitente->crt              = "1";
        $emitente->cnpj             = "13083676000138";
        $emitente->logradouro       = "Centro";
        $emitente->numero           = "46";
        $emitente->complemento      = "";
        $emitente->bairro           = "Distrito Industrial";
        $emitente->ibge             = "2402709";
        $emitente->cidade           = "Cerro Corá";
        $emitente->uf               = "RN";
        $emitente->cep              = "59395000";
        $emitente->cPais            = "1058";
        $emitente->xPais            = "Brasil";
        $emitente->fone             = "9832411133";
        $emitente->pCredSN          = null;

        return $emitente;
    }

    public static function certificado(){
        $certificado = new stdClass;
        $certificado->nome_arquivo  = "certificado.pfx";
        $certificado->senha         = "92625400";

        return $certificado;
    }
    
    public static function cliente(){
        $cliente                   = new stdClass;
        $cliente->nome_razao_social= "Usuario de sistema";
        $cliente->cpf_cnpj			= "35732221026";
        $cliente->idEstrangeiro    = "";
        $cliente->tipo_contribuinte= "1";
        $cliente->rg_ie		    = "";
        $cliente->suframa			= null;
        $cliente->im			    = null;
        $cliente->email		        = "";
        $cliente->logradouro		= "Avenida Santa Clara";
        $cliente->numero			= "06";
        $cliente->complemento		= "Quadra S";
        $cliente->bairro		    = "Vila Riod";
        $cliente->ibge			    = "2111300";
        $cliente->cidade			= "São Luís";
        $cliente->uf			    = "MA";
        $cliente->cep			    = "65058357";
        $cliente->cPais		    = "1058";
        $cliente->xPais		    = "Brasil";
        $cliente->fone			    = "";

        return $cliente;
    }   
    

    public static function produto(){
        $produto            = new stdClass;
        $produto->id        = 125;
        $produto->nome      = "Camisa Algodão";
        $produto->ncm       = "61091000";
        $produto->gtin      = "SEM GTIN";
        $produto->preco     = 100;
        $produto->origem    = "0";
        $produto->cest      = null;
        $produto->unidade   = "UNID";
        $produto->tipi      = null;
        $produto->cest      = null;  
        $produto->cbenef    = null;  
        
        return $produto;

    }

    public static function tributacao(){
        $tributacao = new stdClass;
        $tributacao->cfop = "6102";
        $tributacao->cstIPI = "53";
        $tributacao->clEnq = "";
        $tributacao->CNPJProd = "";
        $tributacao->cSelo = "";
        $tributacao->qSelo = "";
        $tributacao->cEnq = "";  
        $tributacao->pIPI = "";
        $tributacao->vUnidIPI = "";
        $tributacao->qUnidIPI = "";
        $tributacao->tipo_calc_ipi = "1";

        $tributacao->cstPIS = "07";
        $tributacao->pPIS = "";
        $tributacao->vAliqProd_pis = "";

        $tributacao->cstCOFINS = "07";
        $tributacao->pCOFINS = "";
        $tributacao->vAliqProd_cofins = "";

        $tributacao->cstICMS = "00";
        $tributacao->modBC = "3";
        $tributacao->vBCICMS = "";
        $tributacao->pICMS = "18";
        $tributacao->modBCST = "";
        $tributacao->pMVAST = "";
        $tributacao->pRedBCST = "";
        $tributacao->pICMSST = "";
        $tributacao->pRedBC = "";
        $tributacao->motDesICMS = "";
        $tributacao->UFST = "";
        $tributacao->pCredSN = "";
        $tributacao->pFCP = "";
        $tributacao->vICMSSubstituto = "";
        $tributacao->pFCPST = "";
        $tributacao->pFCPSTRet = "";
        $tributacao->pDif = "";

        $tributacao->iva_cstIcms = ""; 
        $tributacao->iva_uf_origem = ""; 
        $tributacao->iva_uf_destino = ""; 
        $tributacao->iva_pIcmsIntra = ""; 
        $tributacao->iva_pIcmsInter = ""; 
        $tributacao->iva_pMVAST = ""; 
        $tributacao->iva_pRedBCST = ""; 
        $tributacao->iva_pFCPST = ""; 
        $tributacao->iva_pDifal = ""; 
        $tributacao->iva_modBCST = ""; 

        return $tributacao;
    }


}