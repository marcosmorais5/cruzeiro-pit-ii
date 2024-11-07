	<nav class="navbar navbar-expand-lg navbar-dark bg-dark text-white">
	  <!-- <a class="navbar-brand" href="#">Menu Principal</a> -->
	  <a class="navbar-brand text-white" href="#">
		<img src="img/logo-menu-navbar.png" height="30" class="d-inline-block align-top" alt="">
		Sistema SOC
	  </a>
  
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	  </button>
	  <div class="collapse navbar-collapse" id="navbarNavDropdown">
		<ul class="navbar-nav">
		  <li class="nav-item navbar-brand ">
			<a class="nav-link text-white" href="index.php">Início</a>
		  </li>
		
		<?php
		/** INICIO: Este menu só aparece para quem não é do grupo CAIXA */
		if($_SESSION['grupo'] != "CAIXA"){ ?>
		  
		  <li class="navbar-brand nav-item dropdown">
			<a class="nav-link dropdown-toggle text-white" href="#" id="navbarCirurgia" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			  &nbsp; Orçamento &nbsp;
			</a>
			<div class="dropdown-menu" aria-labelledby="navbarCirurgia">
			  <a class="dropdown-item" href="orcamento_cadastro.php">Cadastrar orçamento</a>
			  <a class="dropdown-item" href="orcamento_fila.php">Ver sua fila de orçamento</a>
			  
			</div>
		  </li>
		  <?php
		  /** INICIO: Os relatórios só aparecem para usuário ADMINISTRADOR ou MESTRE */
		  if(
				$_SESSION['grupo'] == "ADMINISTRADOR" ||
				$_SESSION['grupo'] == "ADMINISTRADOR_MESTRE"
			){ ?>
		  <li class="navbar-brand nav-item dropdown">
			<a class="nav-link dropdown-toggle text-white" href="#" id="navbarMasterData" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			  &nbsp; Relatório &nbsp;
			</a>
			<div class="dropdown-menu" aria-labelledby="navbarMasterData">
				
			
			  <a class="dropdown-item" href="relatorio_dashboard.php">Dashboard</a>
			  <a class="dropdown-item" href="relatorio_geral.php">Relatório Geral</a>
			  

			  
			</div>
		  </li>
		<?php }
		/** FIM: Os relatórios só aparecem para usuário ADMINISTRADOR ou MESTRE */
		?>
		  
		  
		  <li class="navbar-brand nav-item dropdown">
			<a class="nav-link dropdown-toggle text-white" href="#" id="navbarMasterData" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			  Dados Mestre
			</a>
			<div class="dropdown-menu" aria-labelledby="navbarMasterData">
				
			<?php
			/** INICIO: Criação de usuários só aparece para usuário ADMINISTRADOR ou MESTRE */
			if($_SESSION['grupo'] != "USUARIO"){ ?>
			  <a class="dropdown-item" href="usuario_lista.php">Lista de Usuários</a>
			  <a class="dropdown-item" href="usuario_cadastro.php">Criar Usuário</a>
			<?php }
			/** FIM: Criação de usuários só aparece para usuário ADMINISTRADOR ou MESTRE */
			?>
			 
			  <a class="dropdown-item" href="cliente_lista.php">Lista de Clientes</a>
			  <a class="dropdown-item" href="cliente_cadastro.php">Criar Cliente</a>
			  
			  <a class="dropdown-item" href="medico_lista.php">Lista de Médicos</a>
			  <a class="dropdown-item" href="medico_cadastro.php">Criar Médico</a>
			  
			  <a class="dropdown-item" href="procedimento_lista.php">Lista de Procedimentos</a>
			  <a class="dropdown-item" href="procedimento_cadastro.php">Criar Procedimento</a>
			  
			  <a class="dropdown-item" href="opme_lista.php">Lista de OPME</a>
			  <a class="dropdown-item" href="opme_cadastro.php">Criar OPME</a>
			  
			</div>
		  </li>
		<?php }
		/** FIM: Este menu só aparece para quem não é do grupo CAIXA */
		?>

		  <li class="navbar-brand nav-item">
			<a class="nav-link" href="logon.php" style="color: #FFFF00">Sair</a>
		  </li>
		  
		</ul>
	  </div>
	</nav>