<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email Confirmation Mail</title>
</head>
<body>
    <table border="0" width="430" cellspacing="0" cellpadding="0" style="border-collapse:collapse;margin:0 auto 0 auto" >
                <tbody>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <p style="margin:10px 0 10px 0;color:#565a5c;font-size:18px">Hi {{$details['user_name']}},</p>
                            <p style="margin:10px 0 10px 0;color:#565a5c;font-size:18px">We got a Request for New Account Please On SocialSite</p>
                            <p style="margin:10px 0 10px 0;color:#565a5c;font-size:18px">Click link bellow or use Link to varify your Email.</p>
                        </td>
                    </tr>
                    <tr></tr>
                    <tr>
                        <td height="10" style="line-height:10px" colspan="1">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <a href="{{ $details['link'] }}" style="text-decoration:none;">
                                <strong style="color:#ffff;text-decoration:none;display:block;width: 13em;text-align:center;background:#47a2ea;padding:0.5em;font-size:20px;margin-left: 4em; ">Verify Now</strong>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td height="10" style="line-height:10px" colspan="1">&nbsp;
                               <p style="margin:10px 0 10px 0;color:#565a5c;font-size:18px">{{ $details['link'] }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
</body>
</html>
