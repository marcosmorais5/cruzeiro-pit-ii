<?php
// Definindo a interface
interface BancoOperacoes {
    public function inserir();
    public function atualizar();
    public function excluir();
    public function getOne();
    public function getUmRegistroChave();
    public function getTodosRegistros($params = null);
}
?>