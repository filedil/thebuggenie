<?php

$retval .= '<table cellpadding=0 cellspacing=0 style="width: 100%;" id="issue_affected_edition_'.$anAffected['a_id'].'_menu">';
$retval .= '<tr>';
$retval .= '<td style="width: 20px; padding: 2px;">' . image_tag('icon_edition.png') . '</td>';
$retval .= '<td style="width: auto; padding: 2px;">' . $anAffected['edition'] . '</td>';
if ($theIssue->canEditFields())
{
	$retval .= '<td style="width: 40px; padding: 2px; text-align: right;">';
	$retval .= '<a href="javascript:void(0);" onclick="removeAffected(' . $anAffected['a_id'] . ', \'edition\');" style="font-size: 10px;">' . __('Remove') . '</a>';
	$retval .= '</td>';
}
$retval .= '</tr>';
$retval .=' </table>';

?>