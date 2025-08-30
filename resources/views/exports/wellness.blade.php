<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Clinic Recommendation</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<style>
  :root {
    --text:#1f2937; --muted:#6b7280; --border:#e5e7eb; --accent:#0ea5e9;
  }
  @page { size: A4; margin: 20mm 16mm 18mm; }
  * { box-sizing:border-box; }
  body { font-family: Arial, Helvetica, sans-serif; color:var(--text); font-size:12pt; line-height:1.35; margin:0; }
  .header { display:flex; align-items:center; gap:14px; border-bottom:2px solid var(--border); padding-bottom:10px; }
  .logo { width:64px; height:64px; object-fit:contain; border-radius:8px; border:1px solid var(--border); }
  .h-meta { flex:1; }
  .clinic-name { font-size:18pt; font-weight:bold; letter-spacing:.3px; }
  .clinic-sub { color:var(--muted); font-size:10pt; }

  h1.title { margin:14px 0 10px; font-size:16pt; text-align:center; text-transform:uppercase; letter-spacing:1px; }
  .meta { width:100%; border:1px solid var(--border); border-radius:8px; padding:10px; margin:10px 0 14px; }
  .grid { display:grid; grid-template-columns: 1fr 1fr; gap:8px 16px; }
  .row { display:flex; gap:6px; }
  .label { width:140px; color:var(--muted); font-size:10pt; }
  .value { flex:1; border-bottom:1px dashed var(--border); padding-bottom:2px; }

  .section { margin:16px 0; }
  .section h2 { font-size:12pt; margin:0 0 6px; border-left:3px solid var(--accent); padding-left:8px; }
  .box { border:1px solid var(--border); border-radius:8px; padding:12px; min-height:120px; }
  .small { font-size:10pt; color:var(--muted); }
  .attachments li { margin:4px 0; }

  .sig-wrap { display:grid; grid-template-columns: 1fr 1fr; gap:24px; margin-top:22px; }
  .sig { text-align:center; margin-top:40px; }
  .sig .line { border-top:1px solid var(--text); margin-top:46px; padding-top:6px; font-weight:bold; }
  .sig .sub { font-size:10pt; color:var(--muted); }

  .footer { position:fixed; bottom:10mm; left:0; right:0; text-align:center; color:var(--muted); font-size:10pt; }
  .page-num:before { content: counter(page) " / " counter(pages); }

  /* Page breaks for long recommendations or many attachments */
  .page-break { page-break-before: always; }
</style>
</head>
<body>

  <!-- Header -->
  <div class="header">
    <!-- Replace src with your logo path or remove the <img> -->
    <img class="logo" src="{{ public_path($settings['logo']) }}" alt="Logo">
    <div class="h-meta">
      <div class="clinic-name">{{ $settings['clinic_name'] }}</div>
      <div class="clinic-sub">
        {{ $settings['address'] }} ·
        {{ $settings['contact_no'] }} ·
        {{ $settings['email'] }}
      </div>
    </div>
  </div>

  <h1 class="title">Wellness Recommendation</h1>

  <!-- Details -->
  <div class="meta">
    <div class="grid">
      <div class="row"><div class="label">Date</div><div class="value">{{ now()->parse($data->created_at)->format('F d, Y') }}</div></div>

      <div class="row"><div class="label">Company</div><div class="value">{{ $data->company }}</div></div>
      {{-- <div class="row"><div class="label">Contact Person</div><div class="value">{{ $contact_person ?? '' }}</div></div> --}}

      {{-- <div class="row"><div class="label">Patient / Group</div><div class="value">{{ $subject ?? 'Employees' }}</div></div> --}}
      {{-- <div class="row"><div class="label">Prepared By</div><div class="value">{{ $prepared_by ?? auth()->user()->name ?? '' }}</div></div> --}}
    </div>
  </div>

  <!-- Recommendation -->
  <div class="section">
    <h2>Recommendation</h2>
    <div class="box">
      {!! $data->recommendation !!}
    </div>
    <div class="small" style="margin-top:6px;">
      Lorem Ipsum
      {{-- Note: This recommendation is based on available information at the time of issuance. --}}
    </div>
  </div>

  <!-- Attachments -->
  @if(!empty($data->files) && is_array($data->files))
  <div class="section">
    <h2>Attachments</h2>
    <div class="box">
      <ol class="attachments">
        @foreach($data->files as $i => $file)
          <li>
            <span>{{ basename($file) }}</span>
            <span class="small">({{ $file }})</span>
          </li>
        @endforeach
      </ol>
    </div>
  </div>
  @endif

  <!-- Footer -->
  <div class="footer">
    {{ $settings['clinic_name'] }} — lorem ipsum · Page <span class="page-num"></span>
  </div>

</body>
</html>