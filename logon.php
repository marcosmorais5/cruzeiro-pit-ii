<?php
session_start();
session_destroy();
?>
<html>
<head>
	
	<?php require_once("inc/header.php"); ?>


	<style>
	html,
body {
  height: 100%;
}

body {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-align: center;
  align-items: center;
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #f5f5f5;
}

.form-signin {
  width: 100%;
  max-width: 420px;
  padding: 15px;
  margin: auto;
}

.form-label-group {
  position: relative;
  margin-bottom: 1rem;
}

.form-label-group > input,
.form-label-group > label {
  height: 3.125rem;
  padding: .75rem;
}

.form-label-group > label {
  position: absolute;
  top: 0;
  left: 0;
  display: block;
  width: 100%;
  margin-bottom: 0; /* Override default `<label>` margin */
  line-height: 1.5;
  color: #495057;
  pointer-events: none;
  cursor: text; /* Match the input under the label */
  border: 1px solid transparent;
  border-radius: .25rem;
  transition: all .1s ease-in-out;
}

.form-label-group input::-webkit-input-placeholder {
  color: transparent;
}

.form-label-group input:-ms-input-placeholder {
  color: transparent;
}

.form-label-group input::-ms-input-placeholder {
  color: transparent;
}

.form-label-group input::-moz-placeholder {
  color: transparent;
}

.form-label-group input::placeholder {
  color: transparent;
}

.form-label-group input:not(:placeholder-shown) {
  padding-top: 1.25rem;
  padding-bottom: .25rem;
}

.form-label-group input:not(:placeholder-shown) ~ label {
  padding-top: .25rem;
  padding-bottom: .25rem;
  font-size: 12px;
  color: #777;
}

/* Fallback for Edge
-------------------------------------------------- */
@supports (-ms-ime-align: auto) {
  .form-label-group > label {
    display: none;
  }
  .form-label-group input::-ms-input-placeholder {
    color: #777;
  }
}

/* Fallback for IE
-------------------------------------------------- */
@media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
  .form-label-group > label {
    display: none;
  }
  .form-label-group input:-ms-input-placeholder {
    color: #777;
  }
}

</style>
	<script>
		$(document).ready(function(){
			
			$('[data-toggle="tooltip"]').tooltip();
			
			
			$(".form-signin").submit(function(e){
				
				e.preventDefault();
				
				console.log(UTILS.getJSONtoSave(".logon-fields"));
				
				 $.ajax({
					 url: "logon_action.php",
					 type: 'post',
					 dataType: 'json',
					 cache: false,
					 contentType: 'application/json; charset=utf-8',
					 success: function (data) {
						 
						console.log(data);
						
						if(data.status_code == "OK"){
							
							location.href = "index.php";
							
						}else{
							
							if(typeof(data.status_msg) != 'undefined'){
								
								alert(data.status_msg);
								
							}else{
								
								alert("Usuário ou senha incorreto!")
							}
							
						}
						
					 },
					 error: function(){
					 
						
					 
					 },
					 data: JSON.stringify(UTILS.getJSONtoSave(".logon-fields"))
				});
				
			});

		});
	</script>
	
</head>

<body>


<div class="container-fluid">

	<div class="row">
	
		<div class="col-md-12 mb-3">
				
			<form class="form-signin">
				

				<div class="text-center mb-4">
					<img class="mb-4" src="https://medicosdeolhos.com.br/wp-content/uploads/2020/02/logo-padrao-2.png" alt="" width="350">
					<h1 class="h3 mb-3 font-weight-bold">SOC - Sistema</h1>
				</div>

					
				<div class="form-label-group">
					<input name="user" id="user" class="form-control logon-fields" placeholder="User" required>
					<label for="inputEmail">Usuário (e-mail)</label>
				</div>

				<div class="form-label-group">
					<input type="password" name="password" id="password" class="form-control logon-fields" placeholder="Password" required>
					<label for="inputPassword">Senha</label>
				</div>
				

				<button class="btn btn-lg btn-primary btn-block" type="submit">Acessar o sistema</button>
				
			</form>
		
		</div>
	</div>


</div>
 

 
 
 
 


</body>
</html>