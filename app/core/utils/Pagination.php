<?PHP

namespace App\Core\Utils;

use App\Core\Database\Conexao;
use PDO;

class Pagination
{
    private static $conexao;

    public function __construct()
    {
        self::$conexao = Conexao::conexao();
    }

    public function pagination($sql, $params, $pageAtual, $limit, $webcred = false)
    {
        if ($webcred) {
            self::$conexao = Conexao::conexaoSCM();
        }

        $page = $this->formatPageLimit($pageAtual, $limit);

        if (strpos($sql, 'UNION')) {
            $sqlUnion = "SELECT * FROM ( " . $sql . ") AS tbl  LIMIT " . $page['page'] . "," . $page['limit'];
            $queryObj = self::$conexao->prepare($sqlUnion);
        } else {
            $sql .=   ' LIMIT ' . $page['page'] . ',' . $page['limit']; //add limit
            $queryObj = self::$conexao->prepare($sql);
        }

        // Não utilize bindParam dentro de foreach!
        foreach ($params as $key => $param) {
            $queryObj->bindValue($key, $param);
        }

        $queryObj->execute();

        $result['data'] = $queryObj->fetchAll(PDO::FETCH_OBJ);

        if (strpos($sql, 'UNION')) {
            //$sqlCount = substr($sql,0,strpos($sql,'LIMIT'));

            $explodeSQL = explode("SELECT", $sql);
            $sqlCount = "";
            foreach ($explodeSQL as $key => $value) {
                if (!empty($value)) {
                    $sqlCount .= "SELECT  count(*) AS TOTAL, " . $value;
                }
            }

            $sqlCount = "SELECT SUM(TOTAL) AS TOTAL FROM ( " . $sqlCount . ") AS tbl";
        } else {
            //monta pagination: verifica se existe proxima mais registro
            $sqlCount = substr($sql, 0, strpos($sql, 'LIMIT'));
        }

        $queryObj = self::$conexao->prepare($sqlCount);
        foreach ($params as $key => $param) {
            $queryObj->bindValue($key, $param);
        }

        $queryObj->execute();
        $resultTotal = $queryObj->rowCount();

        $totalPages = ceil($resultTotal / $page['limit']); // arrendonda pra cima por causa se a ultima página ter menos registros q o limit

        $result['pagination']['totalResult'] = $resultTotal;
        $result['pagination']['totalPages'] = $totalPages;
        $result['pagination']['page'] = $pageAtual;
        $result['pagination']['nextPage'] = $pageAtual >= $totalPages ?  $pageAtual :  $pageAtual + 1;
        $result['pagination']['prevPage'] = $pageAtual <= 1 ?  $pageAtual :  $pageAtual - 1;

        return $result;
    }

    private function formatPageLimit($p, $l)
    {
        $page = empty($p) ? 0 : ($p - 1) * $l;
        $limit = empty($l) ? 100 : $l;

        return array("page" => $page, "limit" => $limit);
    }
}
