<?php 

namespace App\Controller\Exceptions;

class ExceptionWeb
{

    public static function setError($message = null, $code = null, $status = false)
    {
        $error = [
            "error" => [
                "message" => empty($message)? ExceptionWeb::listErrorMessages()[$code] : $message,
                "code" => $code,
                "status" => $status
            ]
        ];
        $page = new \App\Controller\Page\Page();
        $page->setTpl("error/error.html", $error);
        
    }

    public static function listErrorMessages()
    {
        return [
            "100" => "Continuar",
            "101" => "Protocolos de Comutação",
            "102" => "Em processamento",
            "103" => "Dicas Antecipadas",
            
            "200" => "Está bem",
            "201" => "Criado",
            "202" => "Aceitaram",
            "203" => "Informações não autorizadas",
            "204" => "Nenhum conteúdo",
            "205" => "Redefinir Conteúdo",
            "206" => "Conteúdo Parcial",
            "207" => "Estado Múltiplo",
            "208" => "Já foi reportado",
            "226" => "Estou acostumado",
            
            "300" => "Escolhas múltiplas",
            "301" => "Movido Permanentemente",
            "302" => "Encontrado",
            "303" => "Ver outro",
            "304" => "Não modificado",
            "305" => "Use Proxy",
            "307" => "Redirecionamento Temporário",
            "308" => "Redirecionamento permanente",
            
            "400" => "Pedido ruim",
            "401" => "Não autorizado",
            "402" => "Pagamento Requerido",
            "403" => "Proibido",
            "404" => "Não encontrado",
            "405" => "Método não permitido",
            "406" => "Não aceitável",
            "407" => "Autenticação de proxy necessária",
            "408" => "Solicitar tempo limite",
            "409" => "Conflito",
            "410" => "Se foi",
            "411" => "Comprimento necessário",
            "412" => "Falha na pré-condição",
            "413" => "Carga útil muito grande",
            "414" => "URI muito longo",
            "415" => "Tipo de Mídia Não Suportado",
            "416" => "Faixa Não Disponível",
            "417" => "Expectativa falhada",
            "421" => "Pedido mal direcionado",
            "422" => "Entidade não processável",
            "423" => "Bloqueado",
            "424" => "Dependência falhada",
            "425" => "Muito cedo",
            "426" => "Upgrade necessário",
            "427" => "Não atribuído",
            "428" => "Pré-requisito exigido",
            "429" => "Muitas solicitações",
            "430" => "Não atribuído",
            "431" => "Solicitar campos de cabeçalho muito grandes	",
            "451" => "Indisponível por motivos legais",
            
            "500" => "Erro Interno do Servidor ",
            "501" => "Não implementado",
            "502" => "Gateway ruim",
            "503" => "Serviço indisponível",
            "504" => "Tempo limite do gateway",
            "505" => "Versão HTTP não suportada",
            "506" => "A Variant também negocia",
            "507" => "Armazenamento insuficiente",
            "508" => "Loop detectado",
            "509" => "Não atribuído",
            "510" => "Não estendido",
            "511" => "Autenticação de rede necessária",
        ];
    }



}