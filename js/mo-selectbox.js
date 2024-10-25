/* Este pacote foi criado para preencher as listas de nomes em diferentes formlários
* O preenchimento utiliza a função helper UTILS.appendOptionsValidationUndefined que atribui ao ID as opções do hash forneceidos
* Ela já valida para ver se o hash é definido ou não, pois muitas vezes, em alguns lugares do programa, nem todos os list são necessários
* */

//if(typeof(json_option_medicos) != 'undefined') UTILS.appendOptions("#idmedico", json_option_medicos, ["idmedico", "nomemedico"]);	
UTILS.appendOptionsValidationUndefined("#idmedico", json_option_medicos, ["idmedico", "nomemedico"]);	

//if(typeof(json_option_clientes) != 'undefined') UTILS.appendOptions("#idcliente", json_option_clientes, ["idcliente", "nome"]);
UTILS.appendOptionsValidationUndefined("#idcliente", json_option_clientes, ["idcliente", "nome"]);

//if(typeof(json_option_tipo_pagamento) != 'undefined') UTILS.appendOptions("#idtipopagamento", json_option_tipo_pagamento, ["idtipopagamento", "tipopagamento"]);
UTILS.appendOptionsValidationUndefined("#idtipopagamento", json_option_tipo_pagamento, ["idtipopagamento", "tipopagamento"]);

//if(typeof(json_option_servico) != 'undefined') UTILS.appendOptions("#idservico", json_option_servico, ["idservico", "servico"]);
UTILS.appendOptionsValidationUndefined("#idservico", json_option_servico, ["idservico", "servico"]);

//if(typeof(json_option_procedimento) != 'undefined') UTILS.appendOptions("#idprocedimento", json_option_procedimento, ["idprocedimento", "procedimento"]);
UTILS.appendOptionsValidationUndefined("#idprocedimento", json_option_procedimento, ["idprocedimento", "procedimento"]);

//if(typeof(json_option_lateralidade) != 'undefined') UTILS.appendOptions("#idlateralidade", json_option_lateralidade, ["idlateralidade", "lateralidade"]);
UTILS.appendOptionsValidationUndefined("#idlateralidade", json_option_lateralidade, ["idlateralidade", "lateralidade"]);

//if(typeof(json_option_status) != 'undefined') UTILS.appendOptions("#idstatus", json_option_status, ["idstatus", "destatus"]);
UTILS.appendOptionsValidationUndefined("#idstatus", json_option_status, ["idstatus", "destatus"]);

//if(typeof(json_option_opme) != 'undefined') UTILS.appendOptions("#idopme", json_option_opme, ["idopme", "opme"]);
UTILS.appendOptionsValidationUndefined("#idopme", json_option_opme, ["idopme", "opme"]);

//if(typeof(json_option_usuario) != 'undefined') UTILS.appendOptions("#idusuario", json_option_usuario, ["idusuario", "nomeusuario"]);
UTILS.appendOptionsValidationUndefined("#idusuario", json_option_usuario, ["idusuario", "nomeusuario"]);

//if(typeof(json_option_grupo) != 'undefined') UTILS.appendOptions("#grupo", json_option_grupo, ["idgrupo", "grupo"]);
UTILS.appendOptionsValidationUndefined("#grupo", json_option_grupo, ["idgrupo", "grupo"]);

//if(typeof(json_option_ativo) != 'undefined') UTILS.appendOptions("#ativo", json_option_ativo, ["idativo", "ativo"]);
UTILS.appendOptionsValidationUndefined("#ativo", json_option_ativo, ["idativo", "ativo"]);






