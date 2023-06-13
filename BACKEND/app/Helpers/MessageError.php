<?php

if (!function_exists('messageError')) {
    function messageError($messages)
    {
        if (is_array($messages)) {
            $responseError = '';
            foreach ($messages as $key => $value) {
                $responseError .= $key . ': ' . $value[0] . ', ';
            }
            return response()->json($responseError, 442);
        }

        throw new Exception("Message Not Array Typed");
    }
}
