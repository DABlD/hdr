<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Clinic Appointment Reminder</title>
</head>

<body style="margin:0; padding:0; font-family: Arial, sans-serif; background-color:#f9f9f9;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="padding:20px 0;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
                    <!-- Header -->
                    <tr>
                        <td align="center" bgcolor="#FFD1A9" style="padding:20px; color:#0B1D39; font-size:20px; font-weight:bold;">
                            <img src="{{ env("APP_URL") . $settings['logo'] }}" alt="Logo" height="30px" style="vertical-align: middle; mix-blend-mode: multiply;">
                            {{ ucwords(strtolower($settings['clinic_name'])) }}
                        </td>
                    </tr>
                    <!-- Body -->
                    <tr>
                        <td style="padding:30px; color:#333333; font-size:15px; line-height:1.6;">
                            <p style="margin:0 0 15px;">
                                Hello <strong>{{ isset($pp) ? $pp->user->fullname : $user->fullname }},</strong>
                            </p>
                            <p style="margin:0 0 15px;">
                                Good day!
                                {{-- This is a friendly reminder for your upcoming appointment: --}}
                            </p>
                            {{-- <table cellpadding="6" cellspacing="0" width="100%" style="margin:15px 0; background:#f4f9f9; border:1px solid #e0e0e0; border-radius:6px;">
                                <tr>
                                    <td style="font-weight:bold; width:120px;">Date:</td>
                                    <td>Test</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;">Time:</td>
                                    <td>Test</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;">Doctor:</td>
                                    <td>Test</td>
                                </tr>
                            </table> --}}
                            <p style="margin:0 0 15px;">
                                This is a reminder from Medhealth Diagnostic Services Inc. regarding your pending APE/PPE/ECU. Please visit the clinic to complete your examination at your most convenient time. 
                                {{-- Please arrive 10–15 minutes early and bring any required documents or lab results. --}}
                            </p>
                            <p style="margin:0 0 25px;">
                                Clinic Hours:
                                <br>
                                {{ $settings['opening_hours'] }}
                            </p>
                            <!-- Button -->
                            {{-- <table cellpadding="0" cellspacing="0" align="center" style="margin:20px auto;">
                                <tr>
                                    <td align="center" bgcolor="#fe6383" style="border-radius:4px;">
                                        <a href="#" target="_blank" style="display:inline-block; padding:12px 20px; font-size:14px; color:#ffffff; text-decoration:none; font-weight:bold;">
                                            Confirm Appointment
                                        </a>
                                    </td>
                                </tr>
                            </table> --}}
                            <p style="margin:20px 0 0; font-size:13px; color:#777;">
                                If you have any questions, concerns or want to make an appointment schedule, please don’t hesitate to get in touch with us at {{ $settings['contact_no'] ?? "" }} / {{ $settings['email'] ?? "" }}
                            </p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td align="center" bgcolor="#A7C7E7" style="padding:15px; font-size:12px; color:#0B1D39;">
                            © {{ date('Y') }} {{ ucwords(strtolower($settings['clinic_name'])) }}. All rights reserved.<br>
                            {{ $settings['address'] }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
