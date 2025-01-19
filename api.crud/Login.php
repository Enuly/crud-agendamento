<?php
// Arquivo de Regras de negócio
require_once 'config.php' ;
class Login 
{   
    //Uma função para verificar ou avalidar login e senha  
    public static function verificarLogin($dados)
    {
        $tabela = "apilogin";
        $colunaLogin = "login";
        $colunaSenha = "senha";

        // conectando com o banco de dados através do PDO
        // pegando as informações do config.php (variáveis globais)        
        $connPdo = new PDO(dbDrive.':host='.dbHost.'; dbname='.dbName, dbUser, dbPass);        
        
        //comando que será executado no banco de dados para avalidar login e senha
        $sql = "select codigoLogin, perfil from $tabela where $colunaLogin=:login and $colunaSenha=:senha" ;
        // preparando o comando Select para ser executado
        $stmt = $connPdo->prepare($sql);
        // configurando o parametro de busca
        $stmt->bindValue(':login' , $dados['login']) ;
        $stmt->bindValue(':senha' , $dados['senha']) ;
        // executando o comando select no banco de dados
        $stmt->execute() ;
        
        if ($stmt->rowCount() > 0)
        {   // se houve retorno de dados
            //var_dump( $stmt->fetch(PDO::FETCH_ASSOC) );

            // retornando os dados do banco de dados
            return $stmt->fetch(PDO::FETCH_ASSOC) ;           
        }else{
            // se não houve retorno de dados
            throw new Exception("Login ou Senha incorreta");
        }

    }
}