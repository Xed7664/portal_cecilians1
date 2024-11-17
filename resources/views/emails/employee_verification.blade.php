<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Portal</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; background-color: #f4f4f4; margin: 0; padding: 0;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
       
            <td style="padding: 40px 30px; background-color: #ffffff;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td style="padding-bottom: 20px;">
                            <h1 style="color: #333333; font-size: 24px; margin: 0;">Dear Employee,</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 20px;">
                            <p style="margin: 0; font-size: 16px; color: #666666;">Welcome to our portal! Here are your temporary credentials:</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 20px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="background-color: #f8f8f8; border-radius: 5px; padding: 15px;">
                                <tr>
                                    <td style="padding: 5px 10px;">
                                        <p style="margin: 0; font-size: 16px; color: #333333;"><strong>Username:</strong> {{ $username }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px 10px;">
                                        <p style="margin: 0; font-size: 16px; color: #333333;"><strong>Password:</strong> (generated)</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 20px;">
                            <p style="margin: 0; font-size: 16px; color: #666666;">Click the button below to set your credentials:</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 20px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td style="border-radius: 5px; background-color: #800000;">
                                        <a href="{{ $url }}" target="_blank" style="display: inline-block; padding: 12px 24px; font-size: 16px; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;">Set Credentials</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="margin: 0; font-size: 16px; color: #666666;">Thank you!</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px; text-align: center; background-color: #eeeeee;">
                <p style="margin: 0; font-size: 14px; color: #888888;">&copy; CodeCrafters. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>
</html>