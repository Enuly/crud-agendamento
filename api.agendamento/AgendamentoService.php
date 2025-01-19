<?php

include 'Agendamento.php';

class AgendamentoService
{
    // Método para consulta de dados.
    public function get($id = null)
    {
       if ($id) {
           return Agendamento::select($id);
       } else {
           return Agendamento::selectAll();
       }
    }

    // Método para inserção de dados.
    public function post()
    {  
       $dados = json_decode(file_get_contents('php://input'), true, 512);         
       if ($dados == null) {
          throw new Exception("Por favor, forneça todas as informações necessárias para o agendamento!");
       }
       return Agendamento::insert($dados);
    }

    // Método para exclusão de dados.
    public function delete($id = null)
    {               
       if ($id == null) {
          throw new Exception("Insira o código do agendamento!");
       }
       return Agendamento::delete($id);
    }

    // Método para alteração de dados.
    public function put($id = null)
    {  
       if ($id == null) {
           throw new Exception("Faltou o código do agendamento!");
       } else {
          $dados = json_decode(file_get_contents('php://input'), true, 512);         
          if ($dados == null) {
             throw new Exception("Adicione as informações para alterar o agendamento!");
          } else {
             return Agendamento::alterar($id, $dados);
          }
       }         
    }

    // Método para verificar a disponibilidade
    public function verificarDisponibilidade($data_hora)
    {
        return Agendamento::verificarDisponibilidade($data_hora);
    }

}
?>
