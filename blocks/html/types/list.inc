<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->types
 */
echo '<table>';

foreach ($this->types as $row) {
    $url = BASE_URI.'/activities?type='.$row['ARB-Type'];
    echo "
    <tr><th>{$row['ARB-Type']}</th>
        <td><a href=\"$url\">{$row['ARB-Descript']}</a></td>
        <td>{$row['ARB-CodeType']}</td>
    </tr>
    ";
}
echo '</table>';