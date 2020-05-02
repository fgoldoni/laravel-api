<hr style="background: #e7e7e7;border: none;height: 1px;margin-top: 30px;">
<p style="
            line-height: 24px;
            font-size: 12px;
            color: #000;
            width:100%;">
<p>
    Sonnige Grüße
    <br/>
    Ihr Team vom EventPortal
    <br/><br/>
    EventPortal GROUP
    <br/><br/>
    EventPortal Deutschland GmbH
    <br/>
    Niendorfer Straße 43 | D-22529 Hamburg | Deutschland
    <br/><br/>
    Vorsitzender des Aufsichtsrates: Goldoni Fouotsa<br/>
    Geschäftsführung: Goldoni Fouotsa (Vorsitzender)
    <br/>
    Sitz der Gesellschaft: Hamburg
    <br/>
    Handelsregister: Amtsgericht Hamburg HRB 66666
</p>
<hr style="background: #e7e7e7;border: none;height: 1px;">
<?php echo app('translator')->get(
    "If you’re having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
    "into your web browser:\n",
    [
        'actionText' => 'Login Link'
    ]
); ?>
<br>
<a href="{{ $url }}">{{ $url }}</a>
