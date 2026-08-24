<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset your password | PLANSI</title>
</head>

<body style="
    margin:0;
    padding:0;
    background:#f7f7f4;
    font-family:Arial, Helvetica, sans-serif;
    color:#1f2925;
">

<table width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="center" style="padding:40px 20px;">

            <table
                width="100%"
                cellpadding="0"
                cellspacing="0"
                role="presentation"
                style="
                    max-width:600px;
                    background:#ffffff;
                    border:1px solid #e6e8e4;
                    border-radius:24px;
                    overflow:hidden;
                "
            >

                <tr>
                    <td style="
                        background:#185c49;
                        padding:32px 40px;
                        text-align:center;
                    ">
                        <div style="
                            font-size:24px;
                            font-weight:700;
                            letter-spacing:5px;
                            color:#ffffff;
                        ">
                            PLANSI
                        </div>

                        <div style="
                            margin-top:6px;
                            font-size:12px;
                            color:#cfe5db;
                        ">
                            Plan what matters.
                        </div>
                    </td>
                </tr>


                <tr>
                    <td style="padding:44px 40px;">

                        <p style="
                            margin:0 0 12px;
                            font-size:14px;
                            font-weight:600;
                            color:#185c49;
                        ">
                            Account Security
                        </p>

                        <h1 style="
                            margin:0;
                            font-size:28px;
                            line-height:1.3;
                            color:#1f2925;
                        ">
                            Reset your password
                        </h1>

                        <p style="
                            margin:18px 0 0;
                            font-size:15px;
                            line-height:1.7;
                            color:#66716c;
                        ">
                            Hi {{ $user->name }},
                        </p>

                        <p style="
                            margin:10px 0 0;
                            font-size:15px;
                            line-height:1.7;
                            color:#66716c;
                        ">
                            We received a request to reset the password for your PLANSI account.
                        </p>


                        <table
                            cellpadding="0"
                            cellspacing="0"
                            role="presentation"
                            style="margin-top:30px;"
                        >
                            <tr>
                                <td
                                    bgcolor="#185c49"
                                    style="border-radius:14px;"
                                >
                                    <a
                                        href="{{ $url }}"
                                        style="
                                            display:inline-block;
                                            padding:15px 26px;
                                            font-size:14px;
                                            font-weight:700;
                                            color:#ffffff;
                                            text-decoration:none;
                                        "
                                    >
                                        Reset Password
                                    </a>
                                </td>
                            </tr>
                        </table>


                        <p style="
                            margin:28px 0 0;
                            font-size:13px;
                            line-height:1.6;
                            color:#89918e;
                        ">
                            This password reset link will expire in 60 minutes.
                        </p>

                        <p style="
                            margin:8px 0 0;
                            font-size:13px;
                            line-height:1.6;
                            color:#89918e;
                        ">
                            If you didn't request a password reset, no action is required.
                        </p>

                    </td>
                </tr>


                <tr>
                    <td style="
                        border-top:1px solid #edf0ed;
                        padding:24px 40px;
                        text-align:center;
                    ">

                        <p style="
                            margin:0;
                            font-size:12px;
                            color:#89918e;
                        ">
                            PLANSI · Plan what matters.
                        </p>

                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>