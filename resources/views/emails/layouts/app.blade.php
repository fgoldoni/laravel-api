<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>@yield('title', 'EventPortal')</title>
    <style type="text/css">
        @import url(https://fonts.googleapis.com/css?family=Comfortaa:300,400,700);
        hr{

        }
        body{
            font-family: 'Noto Sans', sans-serif;
        }
    </style>
</head>
<body >
<table style="background:#f1f1f1;width: 100%;padding: 20px 0;">
    <tbody>
    <tr><td>
            <table style="background: none repeat scroll 0 0 #FFFFFF;margin: 0 auto;padding: 10px 30px 0;width: 90%;max-width: 600px;">
                <tbody>
                <tr>
                    <td  style="padding:20px 0 0 0">
                        <table style="width: 100%;">
                            <tbody>
                            <tr>
                                <td style="color: #7f7f7f;text-align: center;">
                                    <a href="{{ $homeUrl }}">
                                        <img width="200" class="navbar-brand" src="{{ asset('/images/logo.png') }}">
                                    </a>
                                    <hr style="background: #e7e7e7;border: none;height: 1px;margin-top: 30px;"/>
                                </td>
                            </tr>
                            <tr height='5'></tr>
                            <tr>
                                <td>
                                    @yield('content')
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td></tr>
    </tbody>
</table>
</body>
</html>
