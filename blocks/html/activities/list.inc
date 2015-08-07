<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->activities
 */
echo "Found ".count($this->activities)." activities\n";
foreach ($this->activities as $activityNum => $a) {
    echo "
    <div>
        <h2>$activityNum - $a[title]</h2>
        <p>$a[description]</p>
        </p>
        <table>
    ";
    foreach ($a['sections'] as $letter=>$s) {
        echo "
            <tr><th>$activityNum - $letter</th>
                <td>$s[sectionName]</td>
                <td>$s[startDate] - $s[endDate]</td>
                <td>$s[days]</td>
                <td>$s[startTime] - $s[endTime]</td>
                <td>$s[facility]</td>
                <td>$s[ages]</td>
            </tr>
        ";
    }
    echo "
        </table>
    </div>
    ";
}
