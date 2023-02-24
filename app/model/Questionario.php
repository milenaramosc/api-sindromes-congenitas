<?php

namespace App\Model;

use App\Core\Helpers\ModelHelper;
use PDO;
use PDOException;

class Questionario extends ModelHelper
{
  private static $conexao;

  public function __construct()
  {
    parent::__construct();
    self::$conexao = $this->pdo;
  }
  public function inserirDadosQuestionario($resposta)
  {
    try {

      $id_atendimento = (int)$resposta->id_atendimento;
      $idade = (string)$resposta->idade;
      $sexo = (string)$resposta->sexo;
      $peso = (string)$resposta->peso;
      $altura =  (string)$resposta->altura;
      $desenv_cefalico =  (string)$resposta->desenv_cefalico;
      $form_olho = (string)$resposta->form_olho;
      $desenv_extremidade = (string)$resposta->desenv_extremidade;
      $form_pescoco = (string)$resposta->form_pescoco;
      $form_coluna = (string)$resposta->form_coluna;
      $form_mao_pe = (string)$resposta->form_mao_pe;
      $form_labio_palato = (string)$resposta->form_labio_palato;
      $caracteristica_lingua = (string)$resposta->caracteristica_lingua;
      $desenv_pelo = (string)$resposta->desenv_pelo;
      $form_orgao_rep = "";
      (string)$resposta->form_orgao_rep;
      $form_nariz = ""; //(string)$resposta->form_nariz;
      $form_orelha =  (string)$resposta->form_orelha;
      $hist_cardiopatico = (string)$resposta->hist_cardiopatico;
      $idade_materna = (string)$resposta->idade_materna;
      $idade_paterna = (string)$resposta->idade_paterna;
      $aquisicao_linguagem = (string)$resposta->aquisicao_linguagem;
      $atraso_neuropsicomotor = (string)$resposta->atraso_neuropsicomotor;
      $desenv_extremidade = (string)$resposta->desenv_extremidade;

      $sql = "INSERT INTO analise_paciente 
      ( fk_atendimento, idade, sexo, peso, altura, desenv_cefalico, form_olho,
        form_pescoco, form_coluna, form_mao_pe, form_labio_palato, caracteristica_lingua,
        desenv_pelo, form_orgao_rep, form_nariz, form_orelha, hist_cardiopatico, idade_materna,
        idade_paterna, aquisicao_linguagem, atraso_neuropsicomotor, desenv_extremidade
      )
      VALUES(
        :fk_atendimento, :idade, :sexo, :peso, :altura, :desenv_cefalico, :form_olho,
        :form_pescoco, :form_coluna, :form_mao_pe, :form_labio_palato, :caracteristica_lingua,
        :desenv_pelo, :form_orgao_rep, :form_nariz, :form_orelha, :hist_cardiopatico, :idade_materna,
        :idade_paterna, :aquisicao_linguagem, :atraso_neuropsicomotor, :desenv_extremidade
      )";
      $rQuery = self::$conexao->prepare($sql);
      $rQuery->bindParam(":fk_atendimento", $id_atendimento);
      $rQuery->bindParam(":idade", $idade);
      $rQuery->bindParam(":sexo", $sexo);
      $rQuery->bindParam(":peso", $peso);
      $rQuery->bindParam(":altura", $altura);
      $rQuery->bindParam(":desenv_cefalico", $desenv_cefalico);
      $rQuery->bindParam(":form_olho", $form_olho);
      $rQuery->bindParam(":form_pescoco", $form_pescoco);
      $rQuery->bindParam(":form_coluna", $form_coluna);
      $rQuery->bindParam(":form_mao_pe", $form_mao_pe);
      $rQuery->bindParam(":form_labio_palato", $form_labio_palato);
      $rQuery->bindParam(":caracteristica_lingua", $caracteristica_lingua);
      $rQuery->bindParam(":desenv_pelo", $desenv_pelo);
      $rQuery->bindParam(":form_orgao_rep", $form_orgao_rep);
      $rQuery->bindParam(":form_nariz", $form_nariz);
      $rQuery->bindParam(":form_orelha", $form_orelha);
      $rQuery->bindParam(":hist_cardiopatico", $hist_cardiopatico);
      $rQuery->bindParam(":idade_materna", $idade_materna);
      $rQuery->bindParam(":idade_paterna", $idade_paterna);
      $rQuery->bindParam(":aquisicao_linguagem", $aquisicao_linguagem);
      $rQuery->bindParam(":atraso_neuropsicomotor", $atraso_neuropsicomotor);
      $rQuery->bindParam(":desenv_extremidade", $desenv_extremidade);
      $rQuery->execute();
      // $idAtendimento = self::$conexao->lastInsertId();
      // echo "ID_ATENDIMENTO: " . $idAtendimento;
      // return json_encode(["id_atendimento" => $idAtendimento]);
      return $rQuery->rowCount() > 0 ? true : false;
    } catch (PDOException $e) {
    }
  }
}
