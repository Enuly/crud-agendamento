<?php

require_once 'config.php';

class Agendamento 
{
    // 1) Método para fazer consulta de todos os registros sem parâmetro $id.
    public static function selectAll()
    {            
        $tabela = "agendamentos"; 

        $connPdo = new PDO(dbDrive.':host='.dbHost.'; dbname='.dbName, dbUser, dbPass);  

        $sql = "SELECT * FROM $tabela";  
        $stmt = $connPdo->prepare($sql);             
        $stmt->execute(); 
                
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);                
        } else {                
            throw new Exception("Sem registros de agendamentos.");
        }
    }

    // 2) Método para fazer consulta através do parâmetro $id.
    public static function select(int $id)
    {
        $tabela = "agendamentos"; 
        $coluna = "cod_agendamento"; 

        $connPdo = new PDO(dbDrive.':host='.dbHost.'; dbname='.dbName, dbUser, dbPass);            

        $sql = "SELECT * FROM $tabela WHERE $coluna = :id";            
        $stmt = $connPdo->prepare($sql); 
        $stmt->bindValue(':id', $id);           
        $stmt->execute(); 
                  
        if ($stmt->rowCount() > 0) {  
            return $stmt->fetch(PDO::FETCH_ASSOC);                
        } else {
            throw new Exception("Sem registro de agendamento.");
        }
    }

    // 3) Método para fazer inclusão de dados no banco de dados.
    public static function insert($dados)
    {
        $tabela = "agendamentos"; 

        $connPdo = new PDO(dbDrive.':host='.dbHost.'; dbname='.dbName, dbUser, dbPass); 

        $sql = "INSERT INTO $tabela (data_hora, id_usuario, tipo) 
                             VALUES (:data_hora, :id_usuario, :tipo)";
        $stmt = $connPdo->prepare($sql); 
       
        $stmt->bindValue(':data_hora', $dados['data_hora']);
        $stmt->bindValue(':id_usuario', $dados['id_usuario']);
        $stmt->bindValue(':tipo', $dados['tipo']);
        
        $stmt->execute(); 

        if ($stmt->rowCount() > 0) {
           return "Agendamento cadastrado com sucesso!";
        } else {
          throw new Exception("Erro ao inserir o agendamento.");
        }
    }

    // 4) Método para fazer exclusão de um determinado dado.
    public static function delete($id)
    {
        $tabela = "agendamentos"; 
        $coluna = "cod_agendamento";

        $connPdo = new PDO(dbDrive.':host='.dbHost.'; dbname='.dbName, dbUser, dbPass); 

        $sql = "DELETE FROM $tabela WHERE $coluna = :id";
        $stmt = $connPdo->prepare($sql); 
        
        $stmt->bindValue(':id', $id);            
        
        $stmt->execute(); 

        if ($stmt->rowCount() > 0) {
           return "Agendamento excluído com sucesso!";
        } else {
          throw new Exception("Erro ao excluir o agendamento.");
        }
    }

    // 5) Método para fazer a alteração de dados.
    public static function alterar($id, $dados)
    {
        $tabela = "agendamentos"; 
        $coluna = "cod_agendamento";
         
        $connPdo = new PDO(dbDrive.':host='.dbHost.'; dbname='.dbName, dbUser, dbPass); 

        $sql = "UPDATE $tabela SET data_hora = :data_hora, 
                                    id_usuario = :id_usuario, 
                                    tipo = :tipo 
                                WHERE $coluna = :id";
        
        $stmt = $connPdo->prepare($sql); 

        $stmt->bindValue(':data_hora', $dados['data_hora']);
        $stmt->bindValue(':id_usuario', $dados['id_usuario']);
        $stmt->bindValue(':tipo', $dados['tipo']);
        $stmt->bindValue(':id', $id);
        
        $stmt->execute(); 

        if ($stmt->rowCount() > 0) {
           return "Agendamento alterado com sucesso!";
        } else {
          throw new Exception("Erro ao alterar o agendamento.");
        }
    }

    // 6) Método para verificar a disponibilidade de um horário
    public static function verificarDisponibilidade($data_hora)
    {
        $tabela = "agendamentos"; 

        $connPdo = new PDO(dbDrive.':host='.dbHost.'; dbname='.dbName, dbUser, dbPass); 

        // Comando para verificar se já existe um agendamento para a data_hora especificada
        $sql = "SELECT COUNT(*) as count FROM $tabela WHERE data_hora = :data_hora"; 
        $stmt = $connPdo->prepare($sql); 
        $stmt->bindValue(':data_hora', $data_hora);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Retorna true se o horário está disponível, ou false se já está ocupado
        return $resultado['count'] == 0; 
    }
}

?>
