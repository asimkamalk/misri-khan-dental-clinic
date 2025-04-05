{{-- resources/views/admin/prescriptions/pdf.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Prescription #{{ $prescription->id }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 14px;
            line-height: 1.6;
        }

        .prescription {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            border-bottom: 2px solid #4e73df;
            padding-bottom: 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        .clinic-info {
            width: 40%;
        }

        .prescription-title {
            width: 20%;
            text-align: center;
        }

        .doctor-info {
            width: 40%;
            text-align: right;
        }

        .clinic-info h2,
        .doctor-info h2 {
            margin: 0 0 5px 0;
            color: #4e73df;
        }

        .prescription-title h1 {
            margin: 0;
            color: #4e73df;
            font-size: 18px;
        }

        .patient-info {
            background-color: #f8f9fc;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .patient-row {
            display: flex;
            justify-content: space-between;
        }

        .patient-details {
            width: 60%;
        }

        .prescription-meta {
            width: 40%;
            text-align: right;
        }

        .section {
            margin-bottom: 20px;
        }

        .section h3 {
            margin: 0 0 10px 0;
            color: #4e73df;
            font-size: 16px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e3e6f0;
        }

        .section p {
            margin: 0 0 10px 0;
        }

        .footer {
            border-top: 2px solid #4e73df;
            padding-top: 15px;
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }

        .signature {
            width: 40%;
            text-align: right;
        }

        .clinic-stamp {
            width: 60%;
        }
    </style>
</head>

<body>
    <div class="prescription">
        <div class="header">
            <div class="clinic-info">
                <h2>{{ $clinicName }}</h2>
                <p>{{ $clinicAddress }}</p>
                <p>Phone: {{ $clinicPhone }}</p>
            </div>

            <div class="prescription-title">
                <h1>Prescription</h1>
                <p>Date: {{ $prescription->created_at->format('M d, Y') }}</p>
            </div>

            <div class="doctor-info">
                <h2>Dr. {{ $prescription->doctor->name }}</h2>
                <p>{{ $prescription->doctor->specialization }}</p>
                @if ($prescription->doctor->email)
                    <p>{{ $prescription->doctor->email }}</p>
                @endif
                @if ($prescription->doctor->phone)
                    <p>{{ $prescription->doctor->phone }}</p>
                @endif
            </div>
        </div>

        <div class="patient-info">
            <div class="patient-row">
                <div class="patient-details">
                    <p><strong>Patient Name:</strong> {{ $prescription->patient_name }}</p>
                    @if ($prescription->patient_age)
                        <p><strong>Age:</strong> {{ $prescription->patient_age }} years</p>
                    @endif
                    @if ($prescription->patient_gender)
                        <p><strong>Gender:</strong> {{ ucfirst($prescription->patient_gender) }}</p>
                    @endif
                </div>

                <div class="prescription-meta">
                    <p><strong>Prescription ID:</strong> #{{ $prescription->id }}</p>
                    @if ($prescription->followup_date)
                        <p><strong>Follow-up Date:</strong> {{ $prescription->followup_date->format('M d, Y') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="section">
            <h3>Diagnosis</h3>
            <p>{{ $prescription->diagnosis }}</p>
        </div>

        <div class="section">
            <h3>Treatment</h3>
            <p>{!! nl2br(e($prescription->treatment)) !!}</p>
        </div>

        @if ($prescription->medications)
            <div class="section">
                <h3>Medications</h3>
                <p>{!! nl2br(e($prescription->medications)) !!}</p>
            </div>
        @endif

        @if ($prescription->notes)
            <div class="section">
                <h3>Additional Notes</h3>
                <p>{!! nl2br(e($prescription->notes)) !!}</p>
            </div>
        @endif

        <div class="footer">
            <div class="clinic-stamp">
                @if ($prescription->followup_date)
                    <p><strong>Please Return For Follow-up:</strong>
                        {{ $prescription->followup_date->format('M d, Y') }}</p>
                @endif
            </div>

            <div class="signature">
                <p>______________________________</p>
                <p>Dr. {{ $prescription->doctor->name }}</p>
                <p>{{ $prescription->doctor->specialization }}</p>
            </div>
        </div>
    </div>
</body>

</html>
