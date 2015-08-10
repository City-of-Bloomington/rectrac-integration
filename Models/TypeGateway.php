<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Models;

class TypeGateway
{
    public static function find(array $fields=null)
    {
        $data = [];

        $pdo = Database::getConnection();
        $columns = "ARTYPE.\"ARB-Type\",
                    ARTYPE.\"ARB-CodeType\",
                    ARTYPE.\"ARB-Descript\"";
        $from = "from PUB.ARTYPE";
        $where = "where ARTYPE.\"ARB-WebShow\"='yes'";

        if ($fields) {
            if (!empty($fields['CodeType'])) {
                $t = ($fields['CodeType'] === 'C') ? 'C' : 'T';
                $where .= " and ARTYPE.\"ARB-CodeType\"='$t'";
            }
            elseif (!empty($fields['category'])) {
                $c = preg_replace('[^A-Z]', '', $fields['category']);
                if ($c) {
                    $columns = "distinct $columns";
                    $from    = "from PUB.ARSECTION join PUB.ARTYPE on ARSECTION.\"ARS-Type\"=ARTYPE.\"ARB-Type\"";
                    $where   = "where ARSECTION.\"ARS-WebShow\"='yes' and ARSECTION.\"ARS-Category\"='$c'";
                }
            }
        }

        $sql = "select $columns $from $where";
        $result = $pdo->query($sql);

        if (!$result) {
            print_r($pdo->errorInfo());
        }

        return $result;
    }
}