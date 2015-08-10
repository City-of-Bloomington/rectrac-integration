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
        $sql = "select
                    ARTYPE.\"ARB-Type\",
                    ARTYPE.\"ARB-CodeType\",
                    ARTYPE.\"ARB-Descript\"
                from PUB.ARTYPE
                where ARTYPE.\"ARB-WebShow\"='yes'";

        if ($fields) {
            if (!empty($fields['CodeType'])) {
                $t = ($fields['CodeType'] === 'C') ? 'C' : 'T';
                $sql.= " and ARTYPE.\"ARB-CodeType\"='$t'";
            }
        }
        $result = $pdo->query($sql);

        if (!$result) {
            print_r($pdo->errorInfo());
        }

        return $result;
    }
}