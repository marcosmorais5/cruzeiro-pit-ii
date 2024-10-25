/* Este pacote foi criado para preencher as listas de nomes em diferentes formlários
* O preenchimento utiliza a função helper UTILS.appendOptionsValidationUndefined que atribui ao ID as opções do hash forneceidos
* Ela já valida para ver se o hash é definido ou não, pois muitas vezes, em alguns lugares do programa, nem todos os list são necessários
* 
* A validação de cada hash deve ser feita neste arquivo, pois se o HASH for chamado e ele não exisitr, o script para e não preenche os próximos selectbox
* Nem sempre haverão todos os list box para uma tela, portanto, algumas vezes alguns serão "undefined"
*
* */

/* Preencimento do select box de: Médicos */
if(typeof(json_option_medicos) != 'undefined') UTILS.appendOptions("#idmedico", json_option_medicos, ["idmedico", "nomemedico"]);	

/* Preencimento do select box de: Clientes */
if(typeof(json_option_clientes) != 'undefined') UTILS.appendOptions("#idcliente", json_option_clientes, ["idcliente", "nome"]);

/* Preencimento do select box de: Tipo de Pagamento */
if(typeof(json_option_tipo_pagamento) != 'undefined') UTILS.appendOptions("#idtipopagamento", json_option_tipo_pagamento, ["idtipopagamento", "tipopagamento"]);

/* Preencimento do select box de: Tipo de Serviço */
if(typeof(json_option_servico) != 'undefined') UTILS.appendOptions("#idservico", json_option_servico, ["idservico", "servico"]);

/* Preencimento do select box de: Tipo do Procedimento */
if(typeof(json_option_procedimento) != 'undefined') UTILS.appendOptions("#idprocedimento", json_option_procedimento, ["idprocedimento", "procedimento"]);

/* Preencimento do select box de: Lateralidade */
if(typeof(json_option_lateralidade) != 'undefined') UTILS.appendOptions("#idlateralidade", json_option_lateralidade, ["idlateralidade", "lateralidade"]);

/* Preencimento do select box de: Status de orçamento */
if(typeof(json_option_status) != 'undefined') UTILS.appendOptions("#idstatus", json_option_status, ["idstatus", "destatus"]);

/* Preencimento do select box de: OPME */
if(typeof(json_option_opme) != 'undefined') UTILS.appendOptions("#idopme", json_option_opme, ["idopme", "opme"]);

/* Preencimento do select box de: Usuário */
if(typeof(json_option_usuario) != 'undefined') UTILS.appendOptions("#idusuario", json_option_usuario, ["idusuario", "nomeusuario"]);

/* Preencimento do select box de: Grupo */
if(typeof(json_option_grupo) != 'undefined') UTILS.appendOptions("#grupo", json_option_grupo, ["idgrupo", "grupo"]);

/* Preencimento do select box de: Ativo ou não */
if(typeof(json_option_ativo) != 'undefined') UTILS.appendOptions("#ativo", json_option_ativo, ["idativo", "ativo"]);






