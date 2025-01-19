<?php

require_once 'config.php';

      class Usuario 
      {

        //1) Método para fazer consulta de todos os registros sem parâmetro $id.
        public static function selectAll()
        {            
            $tabela = "usuario"; 

            $connPdo = new PDO(dbDrive.':host='.dbHost.'; dbname='.dbName, dbUser, dbPass);  

            $sql = "select * from $tabela" ;  
            $stmt = $connPdo->prepare($sql);             
            $stmt->execute() ; 
                    
            if ($stmt->rowCount() > 0){
                return $stmt->fetchAll(PDO::FETCH_ASSOC) ;                
            }else{                
                throw new Exception("Sem registro do usuario");
            }
        }

        //2) Método para fazer consulta atráves do parâmetro $id.
        public static function select(int $id)
        {
            $tabela = "usuario"; 
            $coluna = "id_usuario"; 

            $connPdo = new PDO(dbDrive.':host='.dbHost.'; dbname='.dbName, dbUser, dbPass);            

            $sql = "select * from $tabela where $coluna = :id" ;            
            $stmt = $connPdo->prepare($sql); 
            $stmt->bindValue(':id' , $id) ;           
            $stmt->execute() ; 
                      
            if ($stmt->rowCount() > 0) {  
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
            // Modificado para retornar resposta mais clara em JSON
                return ["status" => "error", "message" => "Usuário não encontrado"];
            }
        }

        //3) Método para fazer inclusao de dados no Banco de dados.
        public static function insert($dados)
        {
            $tabela = "Usuario"; 

            $connPdo = new PDO(dbDrive.':host='.dbHost.'; dbname='.dbName, dbUser, dbPass); 

            $sql = "INSERT INTO $tabela (nome_usuario, email, telefone, cpf, data_nascimento, senha) 
                    VALUES (:nome_usuario, :email, :telefone, :cpf, :data_nascimento, :senha)";
            $stmt = $connPdo->prepare($sql); 
        
            $stmt->bindValue(':nome_usuario', $dados['nome_usuario']);
            $stmt->bindValue(':telefone', $dados['telefone']);
            $stmt->bindValue(':cpf', $dados['cpf']);
            $stmt->bindValue(':email', $dados['email']);
            $stmt->bindValue(':data_nascimento', $dados['data_nascimento']);
            $stmt->bindValue(':senha', $dados['senha']);
            
            $stmt->execute(); 

            if ($stmt->rowCount() > 0){
                // Retorna a resposta em formato JSON
                return json_encode(["status" => "success", "message" => "Dados cadastrados com sucesso!"]);
            }
            else{
                // Retorna a resposta de erro em formato JSON
                return json_encode(["status" => "error", "message" => "Erro ao inserir os dados!"]);
            }
        }

        //4) Método para fazer exclusão de um determiando dado.
        public static function delete($id)
        {
            $tabela = "usuario"; 
            $coluna = "id_usuario";

            $connPdo = new PDO(dbDrive.':host='.dbHost.'; dbname='.dbName, dbUser, dbPass); 

            $sql = "delete from $tabela where $coluna = :id";
            $stmt = $connPdo->prepare($sql); 
            
            $stmt->bindValue(':id', $id) ;            
            
            $stmt->execute() ; 

            if ($stmt->rowCount() > 0){
               return "Dados excluidos com sucesso!" ;
            }
            else{
              throw new Exception("Erro ao excluir os dados!");
            }
        }

        //5)Método para fazer a alteração de dados.
        public static function alterar($id,$dados)
        {
            $tabela = "usuario"; 
            $coluna = "id_usuario";
             
            $connPdo = new PDO(dbDrive.':host='.dbHost.'; dbname='.dbName, dbUser, dbPass); 

            $sql = "update $tabela set nome_usuario=:nome_usuario, 
                                            telefone=:telefone, 
                                            cpf=:cpf, 
                                            email=:email, 
                                            data_nascimento=:data_nascimento";
            
            // Se a senha foi passada
            if (isset($dados['senha'])) {
                $sql .= ", senha = :senha";
            }

            $sql .= " WHERE $coluna = :id";
            
            $stmt = $connPdo->prepare($sql); 

            $stmt->bindValue(':nome_usuario', $dados['nome_usuario']);
            $stmt->bindValue(':telefone', $dados['telefone']);
            $stmt->bindValue(':cpf', $dados['cpf']);
            $stmt->bindValue(':email', $dados['email']);
            $stmt->bindValue(':data_nascimento', $dados['data_nascimento']);
            $stmt->bindValue(':id', $id);

            // Somente bind se a senha foi passada
            if (isset($dados['senha'])) {
                $stmt->bindValue(':senha', $dados['senha']);
            }
            
            $stmt->execute() ; 

            if ($stmt->rowCount() > 0){
               return "Dados alterados com sucesso!" ;
            }
            else{
              throw new Exception("Erro ao alterar os dados!");
            }
        }

      }

?>