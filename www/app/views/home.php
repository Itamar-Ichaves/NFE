<html lang="pt-br">
<head>
	<meta charset="utf-8"/>
	<title>Método ágora - PHP</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" type="image/x-icon" href="https://mjailton.com.br/imagens_geral/favicon.ico">
	<link rel="stylesheet" href="<?php echo URL_BASE ?>assets/css/auxiliar.css">
	<link rel="stylesheet" href="<?php echo URL_BASE ?>assets/css/grade.css">
	<link rel="stylesheet" href="<?php echo URL_BASE ?>assets/css/style.css">
	<!--icones-->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
		
</head>
<body>
	<div class="conteudo">
		<div class="caixa position-relative">
			<div class="rows mt-5">
				<div class="col-4 m-auto position-absolute">
					<img src="<?php echo URL_BASE ?>assets/img/logo-nfe.png" class="img-fluido">
				</div>
				<div class="col-8 alt">				
					<h1>Curso NFE - Entendendo todos os Campos</h1>
                    <div class="rows">						
						<div class="col-4 mb-3">
							<a href="<?php echo URL_BASE . "nfe/gerar" ?>"  class="btn btn-verde btn-medio width-100" >Gerar o XML</a>
						</div>
                        <div class="col-4 mb-3">
                        <a href="<?php echo URL_BASE . "nfe/assinar" ?>"  class="btn btn-verde btn-medio width-100" >Assinar o XML</a>
						</div>
                        <div class="col-4 mb-3">
                        <a href="<?php echo URL_BASE . "nfe/enviar" ?>"  class="btn btn-verde btn-medio width-100" >Enviar o XML</a>
						</div>
					</div>

					
					<form name="imprimir" action="<?php echo URL_BASE ."nfe/imprimir" ?>" method="Post">
					<div class="rows">
						<div class="col-8 mb-3">
							<input type="text" name="chave" class="form-campo"  placeholder="Insira a chave" />
						</div>
						<div class="col-4 mb-3">
							<input type="submit" class="btn btn-verde btn-medio width-100" value="Inprimir nfe" />
						</div>
					</div>
					</form> 
					
					
					<form name="consultar" action="<?php echo URL_BASE ."nfe/consultar" ?>" method="Post">	
					<div class="rows">	
						<div class="col-8 mb-3">
							<input type="text" name="chave" class="form-campo"  placeholder="Insira a chave" />
						</div>
						<div class="col-4 mb-3">
							<input type="submit" class="btn btn-verde btn-medio width-100" value="Consultar nfe" />
						</div>
					</div>
					</form>
					
					
					<form name="correcao"  action="<?php echo  URL_BASE ."nfe/cancelar" ?>" method="Post">
					<div class="rows">
						<div class="col-8 mb-3">
							<input type="text" name="justitificativa" class="form-campo" placeholder="Insira Justificativa">
						</div>
						<div class="col-4 mb-3">
							<input type="submit" class="btn btn-verde btn-medio width-100" value="Cancelar nfe" />
						</div>
					</div>
					</form>
					
					
					<form name="correcao"  action="<?php echo  URL_BASE ."nfe/correcao" ?>" method="Post">
					<div class="rows">
						<div class="col-8 mb-3">
							<input type="text" name="chave" class="form-campo" placeholder="Insira a chave">
						</div>
						<div class="col-4 mb-3">
							<input type="submit" class="btn btn-verde btn-medio width-100" value="Correção nfe" />
						</div>
					</div>
					</form>
					
					<div class="rows">
					<form name="enviarEmail"  action="<?php echo  URL_BASE ."nfe/email" ?>" method="Post">
						<div class="col-12">						
							<div class="rows">						
								<div class="col-4 mb-3">
									<input type="text" name="" class="form-campo" placeholder="Insira a chave">
								</div>
								<div class="col-5 mb-3 px-0">
									<input type="text" name="" class="form-campo" placeholder="Insira o Email">
								</div>
								<div class="col-3 mb-3">
									<input type="submit" class="btn btn-verde btn-medio width-100" value="Enviar Email" />
								</div>
							</div>
						</div>
					</form>
					</div>
					</div>
				</div>
			</div>
		
		</div>
	</div>
</body>
</html>
