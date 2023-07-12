<?php
namespace app\models\service;

use InvalidArgumentException;
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

class NfeService{
    public static function gerar(){    
        $emitente = DadosService::emitente();  
        $cliente = DadosService::cliente();      
        $produto = DadosService::produto();
        $tributacao = DadosService::tributacao();
        $certificado = DadosService::certificado();
        $ambiente = 2;

        $config = [
            "atualizacao" => date('Y-m-d h:i:s'),
            "tpAmb"       => $ambiente,
            "razaosocial" => $emitente->razao_social,
            "cnpj"        => $emitente->cnpj,
            "siglaUF"     => $emitente->uf,
            "schemes"     => "PL_009_V4",
            "versao"      => '4.00',
            "tokenIBPT"   => "",
            "CSC"         => "",
            "CSCid"       => "",
            "proxyConf"   => [
                "proxyIp"   => "",
                "proxyPort" => "",
                "proxyUser" => "",
                "proxyPass" => ""
            ]
        ];
        $certificadoDigital = file_get_contents(URL_XML."/arquivos/certificado/".$certificado->nome_arquivo);  

        $tools = new Tools(
            json_encode($config),
            Certificate::readPfx(
                $certificadoDigital,
                $certificado->senha
            )
        );



        $nfe = new Make();

        $std = new stdClass();
        $std->versao = '4.00'; //versão do layout (string)
        $std->Id = Null; //se o Id de 44 digitos não for passado será gerado automaticamente
        $std->pk_nItem = null; //deixe essa variavel sempre como NULL

        $nfe->taginfNFe($std);

        $std = new stdClass();
        $std->cUF       = getCodUF($emitente->uf);
        $std->cNF       = rand(11111111, 99999999);
        $std->natOp     = 'Venda de Mercadoria';

        //$std->indPag = 0; //NÃO EXISTE MAIS NA VERSÃO 4.00

        $std->mod       = 55;
        $std->serie     = 1;
        $std->nNF       = 1310;
        $std->dhEmi     = date('Y-m-d') ."T".date('H:i:s')."-03:00";
        $std->dhSaiEnt  = null;
        $std->tpNF      = 1; //Tipo de Nota Fiscal
        $std->idDest    = 2;
        $std->cMunFG    = $emitente->ibge;
        $std->tpImp     = 1;
        $std->tpEmis    = 1;
        $std->cDV       = null;
        $std->tpAmb     = $ambiente;
        $std->finNFe    = 1;
        $std->indFinal  = 1;
        $std->indPres   = 1;
        $std->indIntermed = null;
        $std->procEmi = 0;
        $std->verProc = '3.10.31';
        $std->dhCont = null;
        $std->xJust = null;

        $nfe->tagide($std);        

        //EMITENTE
        $std            = new stdClass();
        $std->xNome     = tiraAcento($emitente->razao_social);
        $std->xFant     = tiraAcento($emitente->nome_fantasia);
        $std->IE        = ($emitente->ie) ? tira_mascara($emitente->ie) : null ;
        $std->IEST      = null;
        $std->IM        = null;
        $std->CNAE      = ($emitente->cnae) ? tira_mascara($emitente->cnae) : null ;
        $std->CRT       = $emitente->crt;
        $std->CNPJ      = tira_mascara($emitente->cnpj) ; 
        $tagemit        = $nfe->tagemit($std);


        $std            = new stdClass();
        $std->xLgr		= tiraAcento(limita_caracteres($emitente->logradouro,45))	;
        $std->nro		= $emitente->numero	    ;
        $std->xCpl		= tiraAcento($emitente->complemento)	;
        $std->xBairro   = tiraAcento(limita_caracteres($emitente->bairro,45))	;
        $std->cMun		= $emitente->ibge	;
        $std->xMun		= tiraAcento($emitente->cidade)	;
        $std->UF		= $emitente->uf		;
        $std->CEP		= $emitente->cep	    ;
        $std->cPais		= "1058"	;
        $std->xPais		= "Brasil"	;
        $std->fone		= $emitente->fone    ;   
        $tagenderEmit   = $nfe->tagenderEmit($std);
 
        //FIM EMITENTE

        //Destinatário

        $std = new stdClass();
        $std->xNome     = tiraAcento(limita_caracteres($cliente->nome_razao_social,56 )) 	;
        $std->indIEDest	= $cliente->tipo_contribuinte	;
        $std->ISUF	    = $cliente->suframa		;
        $std->IM	    = $cliente->im		;
        $std->email	    = $cliente->email		;
        $cnpj_cpf       = tira_mascara($cliente->cpf_cnpj);
        if(strlen($cnpj_cpf) == 14){
            $std->CNPJ = $cnpj_cpf;
            $std->IE   = tira_mascara($cliente->rg_ie);
            $std->CPF  = null;
        }else{
            $std->CNPJ = NULL;
            $std->CPF  = $cnpj_cpf;
        }

       
        $nfe->tagdest($std);
        $std = new stdClass();
        $std->xLgr	= tiraAcento($cliente->logradouro)		;
        $std->nro	= $cliente->numero		;
        $std->xCpl	= tiraAcento($cliente->complemento)		;
        $std->xBairro= tiraAcento($cliente->bairro)	;
        $std->cMun	= $cliente->ibge		;
        $std->xMun	= tiraAcento($cliente->cidade)		;
        $std->UF	= $cliente->uf		;
        $std->CEP	= tira_mascara($cliente->cep)	;
        $std->cPais	= "1058"		;
        $std->xPais	= "Brasil"		;
        $std->fone	= tira_mascara($cliente->fone)		;
        $nfe->tagenderDest($std);
        //Fim Destinatario

        //Inicio Produto
        $std = new stdClass();
        $std->item          = 1;
        $std->cProd         = $produto->id;
        $std->cEAN          = $produto->gtin;
        $std->xProd         = $produto->nome;
        $std->NCM           = $produto->ncm;
        $std->cBenef        = $produto->cbenef; //incluido no layout 4.00
        $std->EXTIPI        = $produto->tipi;
        $std->CEST          = $produto->cest;
        $std->uCom          = tiraAcento($produto->unidade);
        $std->cEANTrib      = $produto->gtin;

        $std->CFOP          = $tributacao->cfop;
        $std->qCom          = 1;
        $std->vUnCom        = formataNumero($produto->preco); 
        $std->vProd         = formataNumero($produto->preco * $std->qCom);

        $std->uTrib         = tiraAcento($produto->unidade);
        $std->qTrib         = 1;
        $std->vUnTrib       = formataNumero($produto->preco);        ;
        $std->vFrete        = null;
        $std->vSeg          = null  ;
        $std->vDesc         = null;
        $std->vOutro        = null ;
        $std->indTot        = 1;
        $std->xPed          = 125;
        $std->nItemPed      = "1";
        $std->nFCI          = null;   
        $nfe->tagprod($std);

        $vBC                = $std->vProd ;

        //Fim PRoduto

        $std = new stdClass();
        $std->item = 1; //item da NFe
        $std->vTotTrib = $produto->preco;
        $nfe->tagimposto($std);
       
     
        $std = new stdClass();
        $std->item = 1; //item da NFe
        $std->orig  = 0;
        $std->CSOSN = '103';
        $nfe->tagICMSSN($std);

        $std = new stdClass();
        $std->item = 1; //item da NFe
        $std->CST   = '07';
        $nfe->tagPIS($std);

        $std = new stdClass();
        $std->item = 1; //item da NFe
        $std->CST   = '07';
        $nfe->tagCOFINS($std);

        $stdTotal = new stdClass;
        $stdTotal->vBC           =  null;
        $stdTotal->vICMS         =  null;
        $stdTotal->vICMSDeson    =  0.00;
        $stdTotal->vFCP          =  0.00;
        $stdTotal->vBCST         =  0.00;
        $stdTotal->vST           =  0.00;
        $stdTotal->vFCPST        =  0.00;
        $stdTotal->vFCPSTRet     =  0.00;
        $stdTotal->vProd         =  0.00;
        $stdTotal->vFrete        =  0.00;
        $stdTotal->vSeg          =  0.00;
        $stdTotal->vDesc         =  0.00;
        $stdTotal->vII           =  0.00;
        $stdTotal->vIPI          =  0.00;
        $stdTotal->vIPIDevol     =  0.00;
        $stdTotal->vPIS          =  0.00;
        $stdTotal->vCOFINS       =  0.00;
        $stdTotal->vOutro        =  0.00;
        $stdTotal->vNF           =  $vBC;
        $stdTotal->vTotTrib      =  0.00;

        $nfe->tagICMSTot($stdTotal);

        $std           = new stdClass();
        $std->modFrete = "9";
        $nfe->tagtransp($std);

        $stdfat = new stdClass();
        $stdfat->nFat   = '17';
        $stdfat->vOrig  = $stdTotal->vNF;
        $stdfat->vDesc  = 0.0;
        $stdfat->vLiq   = $stdTotal->vNF;
        $fat = $nfe->tagfat($stdfat);

        $std        = new stdClass;
        $std->nDup  = "001" ;
        $std->dVenc = '2023-05-04';
        $std->vDup  = formataNumero($stdTotal->vNF)  ;
        $nfe->tagdup($std);

        $std                 = new stdClass();
        $std->vTroco         = 0.0   ;
        $nfe->tagpag($std);

        $std        = new \stdClass();
        $std->tPag  = '05';
        $std->vPag  = number_format($stdTotal->vNF, 2, '.', '');
        $nfe->tagdetPag($std);

        $stdinfAdic = new stdClass();
        $stdinfAdic->infAdFisco = 'informacoes para o fisco';
        $stdinfAdic->infCpl = 'Curso de Nfe - mjailton.com.br';
        $nfe->taginfAdic($stdinfAdic);

        

        if ($ambiente == 1){
            $PastaAmbiente = 'producao';
        }else{
            $PastaAmbiente = 'homologacao';
        }

        /********************* CRIAÇÃO DO XML ****************/
        $path = "arquivos/xml/{$emitente->cnpj}/{$PastaAmbiente}/temporaria";
        try {
            $nfe->montaNFe();
            $chave  = $nfe->getChave();
            $xml    = $nfe->getXML();
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $Filename = $path . '/' . $chave . '-nfe.xml';
            $response = file_put_contents($Filename, $xml);           

        } catch (\Throwable $th) {
            if($nfe->getErrors() !=null)
                i($nfe->getErrors());
            else            
                i($th->getMessage());
        }
       /***********************FIM CRIAÇÃO XML */

       /************ASSINANDO XML ************/
       try {
            $response_assinado = $tools->signNFe(file_get_contents($Filename));
            $path_assinadas = "arquivos/xml/{$emitente->cnpj}/{$PastaAmbiente}/assinadas";
            $caminho = $path_assinadas . '/' . $chave . '-nfe.xml';
            if (!is_dir($path_assinadas)) {
                mkdir($path_assinadas, 0777, true);
            }
            $resp = file_put_contents($caminho, $response_assinado);
            
            } catch (\Exception $e) {
                //aqui você trata possiveis exceptions
                echo $e->getMessage();
            } 

        /**FIM ASSINANDO XML */

        //**** enviar xml */

        try {
            $idLote = str_pad(100, 15, '0', STR_PAD_LEFT); // Identificador do lote
            //envia o xml para pedir autorização ao SEFAZ
            $resp = $tools->sefazEnviaLote([$response_assinado], $idLote);
            //transforma o xml de retorno em um stdClass
            $st = new Standardize();
            $std = $st->toStd($resp);
            if ($std->cStat != 103) {
                //erro registrar e voltar
                i($std->xMotivo);
            }
            $recibo = $std->infRec->nRec;
            //esse recibo deve ser guardado para a proxima operação que é a consulta do recibo
        
            echo "Recibo: " .$recibo ."<br>";
        } catch (\Exception $e) {
            i($e->getMessage());
        }
       //***** fim envio xml */


       //******CONSULTAR O RECIBO *******/     
        try {        
            //
            $xmlResp = $tools->sefazConsultaRecibo($recibo);
            
            //transforma o xml de retorno em um stdClass
            $st = new Standardize();
            $std = $st->toStd($xmlResp);    
            if ($std->cStat=='103') { //lote enviado
                echo  "O lote ainda está sendo processado";
                       }
            if ($std->cStat=='105') { //lote em processamento
                echo "Lote em processamento, tente mais tarde";
               
            }
            
            if ($std->cStat=='104') { //lote processado (tudo ok)
                if ($std->protNFe->infProt->cStat=='100') { //Autorizado o uso da NF-e
                    $protocolo = $std->protNFe->infProt->nProt;
                    echo "Protocolo: " . $protocolo;

                } elseif (in_array($std->protNFe->infProt->cStat,["110", "301", "302"])) { //DENEGADAS
                    echo  "Denegada";
                     i($std->protNFe->infProt->cStat . ":". $std->protNFe->infProt->xMotivo) ;
                } else { //não autorizada (rejeição)
                    echo  "Rejeitada";
                     i($std->protNFe->infProt->cStat . ":". $std->protNFe->infProt->xMotivo) ;
                }
            } else { //outros erros possíveis
                echo  "Nota Rejeitada";
                 i($std->protNFe->infProt->cStat . ":". $std->protNFe->infProt->xMotivo) ;
            }
            
        } catch (\Exception $e) {
            i($e->getMessage());
        }

     
       /*************FIM CONSULTA RECIBO */

       /** Transmissão Sefaz */

        $req = $response_assinado;
        $res = $xmlResp;

        try {
            $xml_autorizado = Complements::toAuthorize($req, $res);
            $path_autorizadas = "arquivos/xml/{$emitente->cnpj}/{$PastaAmbiente}/autorizadas";
            $caminho_aut = $path_autorizadas . '/' . $chave . '-nfe.xml';
            if (!is_dir($path_autorizadas)) {
                mkdir($path_autorizadas, 0777, true);
            }
            file_put_contents($caminho_aut, $xml_autorizado);
        } catch (\Exception $e) {
            echo "Erro: " . $e->getMessage();
        }

        echo "<br>Chave: " .$chave;



    }

    public static function assinar(){

    }

    public static function enviar(){

    }
  
}

