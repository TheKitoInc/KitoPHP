function blk_html_span_update(name)
{
    try
    {
        parent.document.getElementById(name).innerHTML=document.getElementById(name).innerHTML;
        return true;
    }
    catch(ex)
    {
        return false;
    }
}