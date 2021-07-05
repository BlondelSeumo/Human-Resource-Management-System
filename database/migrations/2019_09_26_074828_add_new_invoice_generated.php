<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewInvoiceGenerated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $email = new \App\Models\EmailTemplate();
        $email->email_id = 'NEW_INVOICE_GENERATED';
        $email->subject = 'New invoice has been generated';
        $email->body = 'Hi,<br/>
                            <br/>
                            
                            Invoice has been generated<br/>
                            <br/>
                            Details are as follows:<br/>
                            <br>
                            <b>Product:</b> ##PRODUCT##<br/>
                            <b>Invoice Number:</b> ##INVOICE_NUMBER##<br/>
                            <b>Amount:</b> ##AMOUNT##<br/>
                            <b>Date Generated:</b> ##DATE_GENERATED##<br/>
                            <b>Due Date:</b> ##DUE_DATE##<br/>
                            <br/>
                            <br/>';
        $email->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
