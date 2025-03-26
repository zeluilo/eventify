<?php

function loadTemplate($fileName, $templateVars = []) {
    if (!is_array($templateVars)) {
        $templateVars = [];
    }

    extract($templateVars);
    ob_start();
    require $fileName;
    $contents = ob_get_clean();
    return $contents;
}
