<?php

namespace app\controllers;
use app\core\Controller;
use app\models\service\NfeService;
use stdClass;

class NfeController extends Controller{ 
   public function gerar(){
      NfeService::gerar();
   }  

   public function assinar(){
      NfeService::assinar();
   }

   public function enviar(){
      NfeService::enviar();
   }

   public function imprimir(){

   }

   public function consultar(){
      
   }

   public function cancelar(){
      
   }

   public function correcao(){
      
   }

   public function email(){
      
   }
        
}
