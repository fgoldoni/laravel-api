@extends('emails.layouts.app')

@section('content')
    <div class="content">
        <table border="0" width="100%" align="center" cellpadding="0" cellspacing="0" class="container590">
            <tr>
                <td align="left" style="color: #888888; width:20px; font-size: 16px; line-height: 24px;">
                    <!-- section text ======-->

                    <p style="line-height: 24px; margin-bottom:15px;">
                        Hallo {{ $name }},
                    </p>

                    <p style="line-height: 24px; margin-bottom:20px;">
                        Ihre Zahlung wurde erfolgreich ausgeführt.
                        <br><br>
                        <p style="line-height: 24px; margin-bottom:10px;">
                        Bestellübersicht
                        </p>
                        <br>
                    <table class="panel" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                        <tr>
                            <td class="panel-item">
                                <strong>Name</strong>
                            </td>
                            <td class="panel-item">
                                <strong>
                                    Quantity
                                </strong>
                            </td>
                            <td class="panel-item">
                                <strong>Unit Price</strong>
                            </td>
                        </tr>
                        @foreach ($detail['items'] as $item)
                            <tr>
                                <td class="panel-item">
                                    {{ $item['name'] }}
                                </td>
                                <td class="panel-item">
                                    {{ $item['quantity'] }}
                                </td>
                                <td class="panel-item">
                                    € {{ $item['price'] }}
                                </td>
                            </tr>
                        @endforeach

                        @foreach ($coupons as $coupon)
                            <tr>
                                <td class="panel-item">
                                </td>
                                <td class="panel-item">
                                    {{ $coupon['type'] }} ({{ $coupon['value'] }})
                                </td>
                                <td class="panel-item">
                                    € {{ $coupon['amount'] }}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="panel-item" colspan="2" style="line-height: 28px; margin-top:10px;">
                                <strong>
                                    Total
                                </strong>
                            </td>
                            <td class="panel-item">
                                <strong>
                                    € {{ $detail['total'] }}
                                </strong>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <a href="{{ $url }}">Mehr Tickets kaufen</a>
                    <br><br>
                        Vielen Dank, dass Sie unsere {{ env('APP_NAME', 'SellFirst Portal') }} verwenden!.
                    </p>

                    @include('emails.layouts.footer', ['url' => $url])
                </td>
            </tr>
        </table>
    </div>
@endsection

