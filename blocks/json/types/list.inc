<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->types
 */
$data = [];
foreach ($this->types as $row) {
    $data[] = [
        'Type'     => $row['ARB-Type'],
        'Descript' => $row['ARB-Descript'],
        'CodeType' => $row['ARB-CodeType']
    ];
}
echo json_encode($data, JSON_PRETTY_PRINT);
