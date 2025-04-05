{{-- resources/views/admin/prescriptions/pdf/footer.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        /* Footer Styles */
        .footer {
            padding: 10px 0;
            border-top: 1px solid #ddd;
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #666;
            width: 100%;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
        }

        .footer-left {
            text-align: left;
        }

        .footer-right {
            text-align: right;
        }

        .signature {
            margin-top: 10px;
            font-weight: bold;
        }

        .clinic-name {
            font-style: italic;
        }

        .doctor-name {
            font-weight: bold;
        }

        .doctor-specialization {
            font-style: italic;
        }

        .page-number {
            text-align: center;
            font-size: 10px;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="footer">
        <div class="footer-content">
            <div class="footer-left">
                <div class="clinic-name">{{ $clinicName }}</div>
                <div>This prescription is valid for 30 days from the date of issue</div>
            </div>
            <div class="footer-right">
                <div class="signature">Dr. {{ $doctorName }}</div>
                <div class="doctor-specialization">{{ $doctorSpecialization }}</div>
            </div>
        </div>
        <div class="page-number">Page {PAGE_NUM} of {PAGE_COUNT}</div>
    </div>
</body>

</html>
