<?php
// Definindo a interface para implementação nas classes
interface BancoOperacoes {
    public function inserir();
    public function atualizar();
    public function excluir();
    public function getOne();
    public function getUmRegistroChave();
    public function getTodosRegistros($params = null);
}
?>