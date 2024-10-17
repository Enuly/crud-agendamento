<?php

  include 'Usuario.php';

  class UsuarioService
  {
    //Método para consulta de dados.
    public function get($id = null)
    {
       if ($id){
           return Usuario::select($id);
       }
       else{
           return Usuario::selectAll();
       }
    }

    //Método para inserção de dados.
    public function post()
    {  
       $dados = json_decode(file_get_contents('php://input'),true,512);         
       if ($dados == null){
          throw new Exception("Por favor, forneça todas as informações necessárias para o cadastro!");
       }
       return Usuario::insert($dados);
    }

    //Método para exclusão de dados. 
    public function delete($id = null)
    {               
       if ($id == null){
          throw new Exception("Insira o código!");
       }
       return Usuario::delete($id);
    }

    //Método para alteração de dados.
    public function put($id = null)
    {  
       if ($id == null){
           throw new Exception("Faltou o código!");
       }
       else {
          $dados = json_decode(file_get_contents('php://input'),true,512);         
          if ($dados == null){
             throw new Exception("Adicione as informações para alterar !");
          }
          else {
             return Usuario::alterar($id,$dados);
          }
       }         
    }

 }
?>