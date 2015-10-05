<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Models;

class ActivityGateway
{
    public static $fields = [
        'activityNum'   => 'ARS-ActvNumb',
        'title'         => 'ARC-Descript',
        'description'   => 'ARS-BrochTxt',
        'sex'           => 'ARS-Sex',
        'sectionLetter' => 'ARS-Section',
        'sectionName'   => 'ARS-PrtDesc',
        'facility'      => 'FRF-PrtDesc',
        'startDate'     => 'ARS-BegDate',
        'endDate'       => 'ARS-EndDAte',
        'startTime'     => 'ARS-BegTime',
        'endTime'       => 'ARS-EndTime',
        'days'          => 'ARS-MeetDay',
        'type'          => 'ARC-Type',
        'category'      => 'ARC-Category'
    ];

    public static function find(array $fields=null)
    {
        $pdo = Database::getConnection();
        $columns = "ARSECTION.\"ARS-ActvNumb\",
                    ARSECTION.\"ARS-Section\",
                    ARCLASS.\"ARC-Descript\",
                    ARSECTION.\"ARS-Sex\",
                    ARSECTION.\"ARS-BrochTxt\",
                    ARSECTION.\"ARS-PrtDesc\",
                    ARSECTION.\"ARS-FacID\",
                    FRFACIL.\"FRF-PrtDesc\",
                    ARSECTION.\"ARS-BegDate\",
                    ARSECTION.\"ARS-BegTime\",
                    ARSECTION.\"ARS-EndDAte\",
                    ARSECTION.\"ARS-EndTime\",
                    ARSECTION.\"ARS-MeetDay\",
                    ARSECTION.\"ARS-BegAge\",
                    ARSECTION.\"ARS-EndAge\",
                    ARCLASS.\"ARC-Type\",
                    ARCLASS.\"ARC-Category\"";
        $from = "from PUB.ARSECTION
                join PUB.ARCLASS on ARSECTION.\"ARS-ActvNumb\"=ARCLASS.\"ARC-ActvNumb\"
                join PUB.FRFACIL on ARSECTION.\"ARS-FacID\"=FRFACIL.\"FRF-Facil\"
                                and ARSECTION.\"ARS-FacLoc\"=FRFACIL.\"FRF-Loc\"";
        $where = "where ARSECTION.\"ARS-WebShow\"='yes'";
        $order = "order by ARCLASS.\"ARC-Descript\"";

        if ($fields) {
            if (!empty($fields['type'])) {
                $t = preg_replace('[^A-Z]', '', $fields['type']);
                $where .= " and ARSECTION.\"ARS-Type\"='$t'";
            }
            if (!empty($fields['id'])) {
                $id = (int)$fields['id'];
                $where = "where ARSECTION.\"ARS-ActvNumb\"=$id";
            }
        }

        $sql = "select $columns $from $where $order";
        $result = $pdo->query($sql);
        if (!$result) {
            print_r($pdo->errorInfo());
        }
        return self::loadResults($result);
    }

    private static function loadResults($result)
    {
        $data = [];
        foreach ($result as $row) {
            $activityNum   = $row[self::$fields['activityNum']];
            $sectionLetter = $row[self::$fields['sectionLetter']];
            if (!isset($data[$activityNum])) {
                $data[$activityNum] = [
                    'title'       => $row[self::$fields['title']],
                    'description' => $row[self::$fields['description']],
                    'type'        => $row[self::$fields['type']],
                    'category'    => $row[self::$fields['category']],
                    'sections' => []
                ];
            }
            $data[$activityNum]['sections'][$sectionLetter] = [
                'sectionName' => $row[self::$fields['sectionName']],
                'facility'    => $row[self::$fields['facility']],
                'startDate'   => date(DATE_FORMAT, strtotime($row[self::$fields['startDate']])),
                'endDate'     => date(DATE_FORMAT, strtotime($row[self::$fields['endDate']])),
                'startTime'   => date(TIME_FORMAT, $row[self::$fields['startTime']]),
                'endTime'     => date(TIME_FORMAT, $row[self::$fields['endTime']]),
                'days'        => self::parseDays($row[self::$fields['days']]),
                'ages'        => self::parseAges((float)$row['ARS-BegAge'],(float)$row['ARS-EndAge'])
            ];
        }
        return $data;
    }

    /**
     * Converts RecTrac dayOfWeek structure to English weekday abbreviations
     *
     * @param string $d
     * @return string
     */
    private static function parseDays($d)
    {
        $days = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];

        $out = [];
        $in = explode(';', $d);
        foreach ($in as $i=>$v) {
            if ($v) { $out[] = $days[$i]; }
        }
        return implode(',', $out);
    }

    /**
     * Creates english translation for the age range specified in RecTrac
     *
     * @param float $start
     * @param float $end
     * @return string
     */
    private static function parseAges($start, $end)
    {
        $s = (int)$start;
        $e = explode('.', (string)$end);

        // RecTrac uses decimal values to denote under a certain age
        if (!empty($e[1])) {
            $tail = "under $e[0]";
            return ($s == 0)
                ? $tail
                : "$s to $tail";
        }
        // 99 means no upper age limit
        elseif ((int)$end == 99) {
            return ($s == 0)
                ? 'All Ages'
                : "$s and up";
        }
        else {
            return ($s == 0)
                ? "$e[0] and under"
                : "$s to $e[0]";
        }
    }
}