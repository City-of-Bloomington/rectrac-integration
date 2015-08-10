<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->types
 */
echo '<table>';

foreach ($this->types as $row) {
    echo "
    <tr><th>{$row['ARB-Type']}</th>
        <td>{$row['ARB-Descript']}</td>
        <td>{$row['ARB-CodeType']}</td>
    </tr>
    ";
}
echo '</table>';