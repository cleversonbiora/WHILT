<? include("topo.php"); ?>
<?php
	$erro = 0;
	if(isset($_POST["cmd"]) && strlen($_POST["cmd"]) > 0){
		if($_POST["cmd"] == "delete"){
			//echo $_POST["id"];
			echo Usuario::deletar($_POST["id"]);
		}elseif($_POST["cmd"] == "ativar"){
			Usuario::ativar($_POST["id"]);
		}else{
			if(strlen($_POST["email"]) < 1){
				$erro = 1;
			}else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
				$erro = 2;
			}else if(strlen($_POST["senha"]) < 6){
				$erro = 3;
			}
			else{
				$user_id = Usuario::getEmail($_POST["email"],$empresaId);
				if($user_id <= 0){
					Usuario::cadastrar($_POST['email'],$_POST["senha"],$empresaId,$_POST["tipo"],"",$_POST['nome']);
				}else{
					$erro = 5;
				}
				
			}
		}
	}
	
?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Professores</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<?if($erro == 1){?>
						<div class="alert alert-danger">
						  Email não preenchido.
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
					<?}else if($erro == 4){?>
						<div class="alert alert-danger">
						  Email já cadastrado.
						</div>
					<?}?>
					<form role="form" method="post">
						<input type="hidden" name="cmd" id="cmd" value="insert">
						<div class="form-group">
							<input class="form-control" id="nome" name="nome" type="text" placeholder="Nome">
						</div>
						<div class="form-group">
							<input class="form-control" id="email" name="email" type="text" placeholder="Email">
						</div>
						<div class="form-group">
							<input class="form-control" id="senha" name="senha" type="password" placeholder="Senha">
						</div>
						<div class="form-group">
							<select class="form-control" id="tipo" name="tipo">
								<option value="2">Professor</option>
								<option value="1">Administrador</option>
							</select>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-success">Salvar</button>
						</div>
					</form>
				</div>
				<!-- /.col-lg-6 (nested) -->
			</div>
            <div class="row">
				<div class="col-lg-12">
                    <div class="panel panel-default">
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Email</th>
                                            <th>Tipo</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
									    <?$arr = Usuario::getTipo(1,$empresaId);
											foreach ($arr as $value) {?>
											<tr>
												<td><? echo $value->nome?></td>
												<td><? echo $value->email?></td>
												<td>Administrador</td>
												<td>
													<? if($value->deletado == 0){ ?>
														<form onsubmit="return confirmar(this);" role="form" method="post"><input type="hidden" class="form-control" id="cmd" value="delete" name="cmd"><input type="hidden" id="id" name="id" value="<?echo $value->id; ?>"><button type="submit" class="btn btn-xs btn-danger">Desativar</button></form>
													<? }else{ ?>
														<form role="form" method="post"><input type="hidden" class="form-control" id="cmd" value="ativar" name="cmd"><input type="hidden" id="id" name="id" value="<?echo $value->id; ?>"><button type="submit" class="btn btn-xs btn-success">Ativar</button></form>
													<? } ?>
												</td>
											</tr>
										<?}?>
									    <?$arr = Usuario::getTipo(2,$empresaId);
											foreach ($arr as $value) {?>
											<tr>
												<td><? echo $value->nome?></td>
												<td><? echo $value->email?></td>
												<td>Professor</td>
												<td>
													<? if($value->deletado == 0){ ?>
														<form onsubmit="return confirmar(this);" role="form" method="post"><input type="hidden" class="form-control" id="cmd" value="delete" name="cmd"><input type="hidden" id="id" name="id" value="<?echo $value->id; ?>"><button type="submit" class="btn btn-xs btn-danger">Desativar</button></form>
													<? }else{ ?>
														<form role="form" method="post"><input type="hidden" class="form-control" id="cmd" value="ativar" name="cmd"><input type="hidden" id="id" name="id" value="<?echo $value->id; ?>"><button type="submit" class="btn btn-xs btn-success">Ativar</button></form>
													<? } ?>
												</td>
											</tr>
										<?}?>
                                        
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
    <script>
		function confirmar(form) {
			return confirm('Deseja realmente desativar este professor?');
		}
	</script>
   <? include("rodape.php"); ?>
   