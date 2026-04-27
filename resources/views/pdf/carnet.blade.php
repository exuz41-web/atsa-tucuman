<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { width: 85.6mm; height: 53.98mm; font-family: DejaVu Sans, Arial, sans-serif; overflow: hidden; background: #fff; color: #2a3547; }
  .carnet { width: 85.6mm; height: 53.98mm; position: relative; overflow: hidden; border: .35mm solid #e5eaef; border-radius: 3mm; background: #fff; }
  .soft { position: absolute; right: -9mm; top: -12mm; width: 34mm; height: 34mm; border-radius: 50%; background: #ecf2ff; }
  .header { position: absolute; left: 4mm; top: 3.5mm; right: 4mm; height: 9mm; }
  .logo { height: 8mm; max-width: 28mm; object-fit: contain; }
  .type { position: absolute; right: 0; top: .7mm; background: #1e3a5f; color: #fff; font-size: 2.35mm; font-weight: bold; padding: 1.3mm 2.6mm; border-radius: 1.2mm; text-transform: uppercase; }
  .small-title { position: absolute; left: 0; top: 8.4mm; font-size: 1.85mm; color: #5a6a85; font-weight: bold; letter-spacing: .35mm; text-transform: uppercase; }
  .photo { position: absolute; left: 5mm; top: 17mm; width: 18mm; height: 18mm; border-radius: 50%; border: 1.2mm solid #ecf2ff; overflow: hidden; background: #dfe7ff; text-align: center; line-height: 16mm; color: #5d87ff; font-size: 6mm; font-weight: bold; }
  .photo img { width: 100%; height: 100%; object-fit: cover; }
  .number-label { position: absolute; left: 5mm; top: 37mm; width: 18mm; text-align: center; font-size: 1.8mm; color: #5a6a85; font-weight: bold; text-transform: uppercase; }
  .number { position: absolute; left: 2.5mm; top: 40mm; width: 23mm; text-align: center; font-size: 2.75mm; color: #1e3a5f; font-weight: bold; }
  .barcode { position: absolute; left: 6mm; top: 44mm; width: 16mm; height: 4.8mm; background: repeating-linear-gradient(90deg, #1e3a5f 0 1px, transparent 1px 2.2px, #1e3a5f 2.2px 3px, transparent 3px 5px); }
  .status { position: absolute; left: 28mm; top: 15.5mm; display: inline-block; padding: 1.2mm 2.8mm; border-radius: 8mm; font-size: 2.15mm; font-weight: bold; }
  .ok { background: #e6fffa; color: #13a384; }
  .bad { background: #eaf8ff; color: #1e3a5f; }
  .name { position: absolute; left: 28mm; top: 22mm; right: 21mm; font-size: 4.2mm; font-weight: bold; color: #2a3547; line-height: 1.12; }
  .data { position: absolute; left: 28mm; top: 31mm; width: 34mm; font-size: 2.45mm; line-height: 1.55; }
  .label { color: #5a6a85; font-weight: bold; display: inline-block; width: 15mm; }
  .value { color: #2a3547; font-weight: bold; }
  .qr-box { position: absolute; right: 5mm; top: 30mm; width: 17mm; padding: 1.5mm; border: .25mm solid #dfe5ef; border-radius: 1.5mm; background: #fff; text-align: center; }
  .qr-img { width: 14mm; height: 14mm; }
  .qr-text { font-size: 1.55mm; color: #5a6a85; font-weight: bold; }
  .red { position: absolute; left: 0; right: 0; bottom: 7mm; height: 1.3mm; background: #49beff; }
  .footer { position: absolute; left: 0; right: 0; bottom: 0; height: 7mm; background: linear-gradient(90deg, #0f2236 0%, #1e3a5f 55%, #5d87ff 100%); color: white; text-align: center; font-size: 1.85mm; font-weight: bold; letter-spacing: .32mm; padding-top: 2.3mm; text-transform: uppercase; }
</style>
</head>
<body>
@php
  $vencido = $afiliado->carnet_vencimiento && $afiliado->carnet_vencimiento->lt(now()->startOfDay());
  $valido = $afiliado->carnet_activo && ! $vencido;
  $logoPath = \App\Models\SiteSetting::logoPublicPath();
  $fotoPath = \App\Support\CarnetSupport::fotoPath($afiliado);
@endphp
<div class="carnet">
  <div class="soft"></div>
  <div class="header">
    <img class="logo" src="{{ $logoPath }}" alt="ATSA">
    <div class="type">Afiliado gremial</div>
    <div class="small-title">Credencial personal e intransferible</div>
  </div>

  <div class="photo">
    @if($fotoPath && is_file($fotoPath))
      <img src="{{ $fotoPath }}" alt="Foto">
    @else
      {{ \App\Support\CarnetSupport::initials($afiliado->name) }}
    @endif
  </div>
  <div class="number-label">N° carnet</div>
  <div class="number">{{ $afiliado->numero_afiliado }}</div>
  <div class="barcode"></div>

  <div class="status {{ $valido ? 'ok' : 'bad' }}">{{ $valido ? 'CARNET VALIDO' : ($vencido ? 'CARNET VENCIDO' : 'INACTIVO') }}</div>
  <div class="name">{{ $afiliado->name }}</div>
  <div class="data">
    <div><span class="label">DNI</span><span class="value">{{ $afiliado->dni ?: 'No registrado' }}</span></div>
    <div><span class="label">Filial</span><span class="value">{{ $afiliado->filial ? $afiliado->filial->name : 'Sede Central' }}</span></div>
    @if($afiliado->lugar_trabajo)
    <div><span class="label">Trabajo</span><span class="value">{{ \Illuminate\Support\Str::limit($afiliado->lugar_trabajo, 22) }}</span></div>
    @endif
    <div><span class="label">Vence</span><span class="value">{{ $afiliado->carnet_vencimiento ? $afiliado->carnet_vencimiento->format('d/m/Y') : '31/12/' . date('Y') }}</span></div>
  </div>

  <div class="qr-box">
    <img class="qr-img" src="data:image/png;base64,{{ $qrCode }}" alt="QR">
    <div class="qr-text">Escanear</div>
  </div>

  <div class="red"></div>
  <div class="footer">Asociación de Trabajadores de la Sanidad Argentina - Tucumán</div>
</div>
</body>
</html>
