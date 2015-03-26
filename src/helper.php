<?php
function d($o, $d = false)
{
    echo '<pre>' . print_r($o, true) . '</pre>';
    if ($d) die;
}