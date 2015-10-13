<?php
if (! function_exists('d')) {
    /**
     * Dump the passed variable
     *
     * @param mixed $o
     * @param bool $d die if TRUE
     * @return void
     */
    function d($o, $d = false)
    {
        echo '<pre>' . print_r($o, true) . '</pre>';
        if ($d) die(1);
    }
}

if (! function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed
     * @return void
     */
    function dd()
    {
        array_map(function ($x) {
            (new Dumper)->dump($x);
        }, func_get_args());

        die(1);
    }
}