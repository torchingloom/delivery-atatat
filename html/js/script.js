
function ClickOnButton(_btn)
{
    $('input[type="submit"], input[type="button"], input[type="reset"]')
        .attr('disabled', '1')
    ;
    return true;
}