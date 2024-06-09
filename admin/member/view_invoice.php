<?php
if($_REQUEST['tab'] == 'view_invoice')
{
    $invoice_type=$_REQUEST['invoice_type'];
    $invoice_id=$_REQUEST['idtest'];
    MJ_cmgt_view_invoice_page($invoice_type,$invoice_id);
}?>