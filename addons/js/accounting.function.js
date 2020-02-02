
function format_integer(nStr) {
    x = accounting.formatMoney(nStr, {
        symbol: "",
        precision: 0,
        decimal : ".",
        thousand: ",",
        format: {
            pos : "%s%v",
            neg : "%s(%v)",
            zero: "%s0"
        }
    });
    return x;
}

function format_decimal(nStr) {
    x = accounting.formatMoney(nStr, {
        symbol: "",
        precision: 2,
        decimal : ".",
        thousand: ",",
        format: {
            pos : "%s%v",
            neg : "%s(%v)",
            zero: "%s0"
        }
    });
    return x;
}

function format_money(nStr) {
    x = accounting.formatMoney(nStr, {
        symbol: "",
        precision: 0,
        decimal : ".",
        thousand: ",",
        format: {
            pos : "%s%v",
            neg : "%s(%v)",
            zero: "%s0"
        }
    });
    return x;
}

function format_money_comma(nStr) {
    x = accounting.formatMoney(nStr, {
        symbol: "",
        precision: 2,
        decimal : ".",
        thousand: ",",
        format: {
            pos : "%s%v",
            neg : "%s(%v)",
            zero: "%s0"
        }
    });
    return x;
}

function clear_thousand(nStr) {
    x = accounting.unformat(nStr, '.');
    return x;
}

function lpad(originalstr, length, strToPad) {
    while (originalstr.length < length) {
        originalstr = strToPad + originalstr;
    }
    return originalstr;
}
 
function rpad(originalstr, length, strToPad) {
    while (originalstr.length < length) {
        originalstr = originalstr + strToPad;
    }
    return originalstr;
}