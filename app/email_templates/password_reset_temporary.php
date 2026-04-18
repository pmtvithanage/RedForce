<?php
$name = htmlspecialchars($name ?? 'User');
$userID = htmlspecialchars($userID ?? '');
$temporaryPassword = htmlspecialchars($temporaryPassword ?? '');
$expiresInMinutes = (int)($expiresInMinutes ?? 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:24px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:10px;padding:24px;">
                    <tr>
                        <td>
                            <h2 style="margin:0 0 12px 0;color:#b30b0b;">Red Force Password Reset</h2>
                            <p style="margin:0 0 10px 0;color:#222;">Hello <?php echo $name; ?>,</p>
                            <p style="margin:0 0 10px 0;color:#222;">A temporary password has been generated for your account.</p>
                            <p style="margin:0 0 4px 0;color:#222;"><strong>User ID:</strong> <?php echo $userID; ?></p>
                            <p style="margin:0 0 14px 0;color:#222;"><strong>Temporary Password:</strong> <?php echo $temporaryPassword; ?></p>
                            <p style="margin:0 0 10px 0;color:#222;">This password is valid for <strong><?php echo $expiresInMinutes; ?> minute</strong>.</p>
                            <p style="margin:0 0 10px 0;color:#222;">Log in immediately and change your password from the change-password section.</p>
                            <p style="margin:0;color:#777;font-size:12px;">If you did not request this reset, please contact support.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
