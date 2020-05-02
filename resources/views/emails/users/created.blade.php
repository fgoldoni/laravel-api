@extends('emails.layouts.app')

@section('content')
    <div class="content">
        <table border="0" width="100%" align="center" cellpadding="0" cellspacing="0" class="container590">
            <tr>
                <td align="left" style="color: #888888; width:20px; font-size: 16px; line-height: 24px;">
                    <!-- section text ======-->

                    <p style="line-height: 24px; margin-bottom:15px;">
                        Liebe Kundin, lieber Kunde!
                    </p>

                    <p style="line-height: 24px; margin-bottom:20px;">
                        Vielen Dank für das Anfordern Ihrer Zugangsdaten zum EventPortal. Sie können sich über den folgenden Link ganz einfach in Ihr Profil einloggen.
                        <br><br>
                        <a href="{{ $url }}">Login Link</a>
                        <br><br>
                        Vielen Dank, dass Sie unsere EventPortal verwenden!.
                    </p>

                    @include('emails.layouts.footer', ['url' => $url])
                </td>
            </tr>
        </table>
    </div>
@endsection

