<? include("topo.php"); ?>
<?php
	$erro = 0;
	if(isset($_POST["cmd"]) && strlen($_POST["cmd"]) > 0){
		if($_POST["cmd"] == "alterar"){
			if(strlen($_POST["cor"]) < 6){
				$erro = 1;
			}
			else{
				$url = $_POST["logo"];
				if(isset($_FILES['logo']['name'])){
					$uploaddir = 'logo/';
					$extensao = pathinfo ( $_FILES['logo']['name'], PATHINFO_EXTENSION );
					$name =  uniqid(rand(), true);
					$uploadfile = $uploaddir . $name . '.'. $extensao;
					$url = "";
					if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadfile)) {
						$url = "https://whilt.co/app/admin/".$uploadfile;
					}
				}
				Empresa::alterar($_POST['cor'],$url);
				$c = Empresa::getId($empresaId);
			}
		}
	}
?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Configurações</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<?if($erro == 1){?>
						<div class="alert alert-danger">
						   Cor inválido.
						</div>
					<?}else if($erro == 2){?>
						<div class="alert alert-danger">
						  Email inválido.
						</div>
					<?}else if($erro == 3){?>
						<div class="alert alert-danger">
						  A senha deve conter no minímo 6 caracteres.
						</div>
					<?}else if($erro == 4){?>
						<div class="alert alert-danger">
						  Falha no cadastro.
						</div>
					<?}?>
					<form role="form" method="post" enctype="multipart/form-data">
						<input type="hidden" name="cmd" id="cmd" value="alterar">
						<div class="form-group">
							<label>Cor</label>
							<input class="form-control" value="<? echo $c->cor;?>" id="cor" name="cor" type="text" placeholder="Cor">
						</div>
						<div class="form-group">
							<label>Logo</label>
							<input value="<? echo $c->logo;?>" id="logo" name="logo" type="hidden">
							<? if(isset($c->logo) && strlen($c->logo) > 0){ ?>
								<img height="90" src="<? echo $c->logo;?>" style="display: block;margin: 10px;"/>
							<? } ?>
							<input id="logo" name="logo" type="file" accept="image/*">
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-success">Salvar</button>
						</div>
					</form>
				</div>
				<!-- /.col-lg-6 (nested) -->
			</div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
   <? include("rodape.php"); ?>
