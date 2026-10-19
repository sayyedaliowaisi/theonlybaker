
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Enquiry</title>
</head>
<body style="margin:0;padding:30px 15px;background:#f8f1e8;font-family:Arial,sans-serif;color:#432819;">

    <div style="max-width:620px;margin:0 auto;background:#fffdf9;border:1px solid #eadfd2;border-radius:10px;overflow:hidden;">

        <div style="padding:32px 25px;background:#432819;text-align:center;color:#fff;">
            <h1 style="margin:0;font-family:Georgia,serif;font-size:28px;font-weight:normal;">
                The Only Baker
            </h1>
            <p style="margin:10px 0 0;color:#e9d4c1;font-size:13px;">
                New Contact Enquiry
            </p>
        </div>

        <div style="padding:30px 25px;">

            <h2 style="font-family:Georgia,serif;font-weight:normal;">
                You've Received a New Message
            </h2>

            <p style="color:#806c5d;line-height:1.7;">
                A customer has submitted an enquiry through
                The Only Baker website.
            </p>

            <table style="width:100%;border-collapse:collapse;font-size:14px;">
                <tr>
                    <td style="padding:12px;border-bottom:1px solid #eee;"><strong>Name</strong></td>
                    <td style="padding:12px;border-bottom:1px solid #eee;">{{ $enquiry->name }}</td>
                </tr>
                <tr>
                    <td style="padding:12px;border-bottom:1px solid #eee;"><strong>Email</strong></td>
                    <td style="padding:12px;border-bottom:1px solid #eee;">{{ $enquiry->email }}</td>
                </tr>
                <tr>
                    <td style="padding:12px;border-bottom:1px solid #eee;"><strong>Phone</strong></td>
                    <td style="padding:12px;border-bottom:1px solid #eee;">{{ $enquiry->phone ?: 'Not provided' }}</td>
                </tr>
                <tr>
                    <td style="padding:12px;border-bottom:1px solid #eee;"><strong>Subject</strong></td>
                    <td style="padding:12px;border-bottom:1px solid #eee;">{{ ucfirst($enquiry->subject) }}</td>
                </tr>
            </table>

            <h3 style="margin-top:30px;font-family:Georgia,serif;">
                Customer Message
            </h3>

            <div style="padding:20px;background:#fff6ec;border-left:3px solid #a46b42;line-height:1.8;white-space:pre-wrap;overflow-wrap:anywhere;">{{ $enquiry->message }}</div>

            <p style="margin-top:30px;color:#998777;font-size:12px;">
                This enquiry was submitted through The Only Baker contact form.
            </p>

        </div>
    </div>

</body>
</html>
