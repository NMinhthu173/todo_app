<?php
function laSoNguyenTo($n) {
    if ($n < 2) return false;
    for ($i = 2; $i <= sqrt($n); $i++) {
        if ($n % $i == 0) return false;
    }
    return true;
}

$so = 11;

if (laSoNguyenTo($so)) {
    echo "$so là số nguyên tố";
} else {
    echo "$so không phải là số nguyên tố";
}
?>