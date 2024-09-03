<?php
require_once("../core/header.php");

function getComboTurma($codigoTurma = false){
    $arDadosTurma = array();
    $dadosTurma = @file_get_contents("../turma/turmas.json");
    if($dadosTurma){
        // transforma os dados lidos em ARRAY, que estavam em JSON
        $arDadosTurma = json_decode($dadosTurma, true);
    }

    $html = '<div style="display:flex;gap:5px;flex-direction:row;">';

    $html .= '  <label for="turma">Turma:</label>';
    $html .= '  <select id="turma" name="turma">';
    $html .= '    <option value="0">Selecione</option>';

    //  preencher com php - mais options de TURMA
    foreach($arDadosTurma as $aDados){
        $selected = "";
        if($codigoTurma == $aDados["codigo"]){
            $selected = " selected ";                    
        }

        $html .= '<option value="'. $aDados["codigo"] .'" ' . $selected .'>' . $aDados["nome"] . '</option>';
    }

    $html .= '</select>';
    $html .= '</div>';

    return $html;
}

function getDadosMateria($codigoMateriaAlterar){
    $nome = "";
    $turma = "";

    // VAI BUSCAR OS DADOS DE Materia.JSON
    $dadosMateria = @file_get_contents("materia.json");

    // TRANSFORMAR EM ARRAY
    $arDadosMateria = json_decode($dadosMateria, true);
    // echo  "<pre>" . print_r($arDadosMateria, true) . "</pre>";
    // return true;

    $encontrouMateria = false;
    foreach($arDadosMateria as $aDados){
        $codigoAtual = $aDados["codigo"];
        if($codigoMateriaAlterar == $codigoAtual){
            $encontrouMateria = true;
            $nome = $aDados["nome"];
            $turma = $aDados["turma"];

            // para a execução do loop
            break;
        }
    }

    return array($nome, $turma, $encontrouMateria);
}

// Verificar se existe acao
$codigo = "";
$nome = "";
$turma = "";
$turmaCombo = "";
$display = "block;";

$encontrouMateria = false;
$mensagemMateriaNaoEncontrado = "";

$acaoFormulario = "INCLUIR";
if(isset($_GET["ACAO"])){
    $acao = $_GET["ACAO"];
    if($acao == "ALTERAR"){
        $acaoFormulario = "ALTERAR";
        
        $display = "none;";
        $codigo = $_GET["codigo"];
        list($nome, $turma, $encontrouMateria) = getDadosMateria($codigo);
        
        if($encontrouMateria){
            // Limpa a mensagem de erro
            $mensagemMateriaNaoEncontrado = "";
        } else {
            // Adiciona o codigo do Materia no fim
            $mensagemMateriaNaoEncontrado = "Não foi encontrado nenhum Materia para o codigo informado! Código: ";
            $mensagemMateriaNaoEncontrado .= $codigo;
        }
    }
}

$turmaCombo = getComboTurma($codigo);

$sHTML = '<div> <link rel="stylesheet" href="../css/formulario.css">';

// FORMULARIO DE CADASTRO DE MateriaS
$sHTML .= '<h2 style="text-align:center;">Formulário de Materia</h2>
    <h3>' . $mensagemMateriaNaoEncontrado . '</h3>
    <form action="cadastrar_materia.php" method="POST">
        <input type="hidden" id="ACAO" name="ACAO" value="' . $acaoFormulario . '">

        <label for="codigo">Código:</label>
        <input type="hidden" id="codigo" name="codigo" value="' . $codigo . '" required>
        <input type="text" id="codigoTela" name="codigoTela" value="' . $codigo . '" disabled>
        
        ' . $turmaCombo . '
        
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required value="' . $nome . '">
        
        <input type="submit" value="Enviar">
    </form>';

// CONSULTA DE MateriaS
$sHTML .= '</div>';

echo $sHTML;

require_once("../core/footer.php");
