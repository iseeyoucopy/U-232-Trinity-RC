<?php
function tr($x, $y, $noesc = 0)
{
    if ($noesc) {
        $a = $y;
    }
    else {
        $a = htmlsafechars($y);
        $a = str_replace("\n", "<br>\n", $a);
    }
    return "<tr>
				<td>$x</td>
				<td>$a</td>
			</tr>";
}

?>