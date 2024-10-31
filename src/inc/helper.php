<?php

function bluebox_repeat_event_get_day_string($days)
{
  $returnStr = 'Occurs every ';
  if (!$days) {
    return '';
  }
  if (count($days) === 1) {
    return $returnStr . $days[0];
  }
  if (count($days) === 7) {
    return $returnStr . ' day';
  }
  if (count($days) === 2) {
    return $returnStr . $days[0] . ' and ' . $days[1];
  }
  foreach ($days as $i => $day) {
    if (count($days) - 2 === $i) {
      //if second to last day in list
      $returnStr .= $day . ' and ';
    } elseif (count($days) - 1 === $i) {
      $returnStr .= $day;
    } else {
      $returnStr .= $day . ', ';
    }
  }
  return $returnStr;
}
