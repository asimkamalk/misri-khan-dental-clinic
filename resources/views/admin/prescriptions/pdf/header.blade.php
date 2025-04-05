{{-- resources/views/admin/prescriptions/pdf/header.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        /* Header Styles */
        .header {
            padding: 10px;
            border-bottom: 2px solid #4e73df;
            margin-bottom: 20px;
            width: 100%;
        }

        .header-content {
            display: flex;
            align-items: center;
        }

        .clinic-logo {
            width: 80px;
            height: auto;
            margin-right: 20px;
        }

        .clinic-info {
            font-family: Arial, sans-serif;
        }

        .clinic-name {
            font-size: 18px;
            font-weight: bold;
            color: #4e73df;
            margin: 0 0 5px 0;
        }

        .clinic-address,
        .clinic-contact {
            font-size: 12px;
            margin: 2px 0;
            color: #666;
        }

        .prescription-title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            color: #4e73df;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="header-content">
            @if (isset($logoPath) && file_exists($logoPath))
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents($logoPath)) }}" class="clinic-logo"
                    alt="Clinic Logo">
            @endif

            <div class="clinic-info">
                <h1 class="clinic-name">{{ $clinicName }}</h1>
                <p class="clinic-address">{{ $clinicAddress }}</p>
                <p class="clinic-contact">
                    Phone: {{ $clinicPhone }}
                    @if (isset($clinicEmail) && $clinicEmail)
                        | Email: {{ $clinicEmail }}
                    @endif
                </p>
            </div>
        </div>
        <div class="prescription-title">MEDICAL PRESCRIPTION</div>
    </div>
</body>

</html>
