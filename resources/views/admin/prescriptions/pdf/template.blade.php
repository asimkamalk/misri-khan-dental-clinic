{{-- resources/views/admin/prescriptions/pdf/template.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Prescription #{{ $prescription->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }

        /* Patient Info Box */
        .patient-info {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        .patient-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-gap: 10px;
        }

        .patient-info-label {
            font-weight: bold;
            color: #4e73df;
        }

        /* Prescription Info */
        .prescription-info {
            margin-bottom: 30px;
        }

        .prescription-date {
            text-align: right;
            font-style: italic;
            margin-bottom: 20px;
        }

        /* Sections */
        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            color: #4e73df;
            font-size: 14px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .section-content {
            padding-left: 15px;
        }

        /* Medications */
        .medication-list {
            list-style-type: none;
            padding-left: 0;
        }

        .medication-item {
            padding: 8px 0;
            border-bottom: 1px dotted #ddd;
        }

        /* Followup */
        .followup {
            margin-top: 30px;
            padding: 10px;
            border: 1px dashed #4e73df;
            border-radius: 5px;
            background-color: #f0f4ff;
        }

        /* QR Code */
        .qr-code {
            text-align: center;
            margin-top: 30px;
        }

        /* Notes */
        .notes {
            margin-top: 20px;
            font-style: italic;
            color: #666;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 25%;
            transform: rotate(-45deg);
            transform-origin: center;
            z-index: -1000;
            font-size: 80px;
            color: rgba(200, 200, 200, 0.2);
        }
    </style>
</head>

<body>
    <!-- Watermark (optional) -->
    <div class="watermark">{{ $clinicName }}</div>

    <!-- Patient Information -->
    <div class="patient-info">
        <div class="patient-info-grid">
            <div>
                <span class="patient-info-label">Patient Name:</span> {{ $prescription->patient_name }}<br>
                @if ($prescription->patient_age)
                    <span class="patient-info-label">Age:</span> {{ $prescription->patient_age }} years<br>
                @endif
                @if ($prescription->patient_gender)
                    <span class="patient-info-label">Gender:</span> {{ ucfirst($prescription->patient_gender) }}<br>
                @endif
            </div>
            <div>
                <span class="patient-info-label">Prescription #:</span> {{ $prescription->id }}<br>
                <span class="patient-info-label">Doctor:</span> Dr. {{ $prescription->doctor->name }}<br>
                <span class="patient-info-label">Date:</span> {{ $prescription->created_at->format('F d, Y') }}
            </div>
        </div>
    </div>

    <!-- Diagnosis Section -->
    <div class="section">
        <div class="section-title">DIAGNOSIS</div>
        <div class="section-content">{{ $prescription->diagnosis }}</div>
    </div>

    <!-- Treatment Section -->
    <div class="section">
        <div class="section-title">TREATMENT</div>
        <div class="section-content">{!! nl2br(e($prescription->treatment)) !!}</div>
    </div>

    <!-- Medications Section -->
    @if ($prescription->medications)
        <div class="section">
            <div class="section-title">MEDICATIONS</div>
            <div class="section-content">{!! nl2br(e($prescription->medications)) !!}</div>
        </div>
    @endif

    <!-- Follow-up Section -->
    @if ($prescription->followup_date)
        <div class="followup">
            <span class="patient-info-label">Please Return For Follow-up:</span>
            {{ \Carbon\Carbon::parse($prescription->followup_date)->format('F d, Y') }}
        </div>
    @endif

    <!-- Notes Section -->
    @if ($prescription->notes)
        <div class="notes">
            <div class="section-title">ADDITIONAL NOTES</div>
            <div class="section-content">{!! nl2br(e($prescription->notes)) !!}</div>
        </div>
    @endif
</body>

</html>
