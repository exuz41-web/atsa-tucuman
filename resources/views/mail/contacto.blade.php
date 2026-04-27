<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
body { font-family: Arial, sans-serif; color: #2a3547; background: #f4f7fb; margin: 0; padding: 0; }
.wrap { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.08); }
.header { background: #1e3a5f; color: #fff; padding: 28px 32px; }
.header h1 { margin: 0; font-size: 20px; }
.body { padding: 32px; }
.field { margin-bottom: 20px; }
.label { font-size: 12px; text-transform: uppercase; color: #5a6a85; font-weight: 700; letter-spacing: .5px; margin-bottom: 4px; }
.value { font-size: 15px; line-height: 1.6; color: #2a3547; }
.message-box { background: #f4f7fb; border-radius: 8px; padding: 16px; font-size: 15px; line-height: 1.7; }
.footer { text-align: center; padding: 20px 32px; font-size: 12px; color: #8a98a5; border-top: 1px solid #ebf1f6; }
</style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <h1>📨 Nueva consulta desde el sitio web</h1>
    </div>
    <div class="body">
        <div class="field">
            <div class="label">Nombre</div>
            <div class="value">{{ $data['nombre'] }}</div>
        </div>
        <div class="field">
            <div class="label">Email</div>
            <div class="value"><a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a></div>
        </div>
        @if (!empty($data['telefono']))
        <div class="field">
            <div class="label">Teléfono</div>
            <div class="value">{{ $data['telefono'] }}</div>
        </div>
        @endif
        <div class="field">
            <div class="label">Asunto</div>
            <div class="value">{{ $data['asunto'] }}</div>
        </div>
        <div class="field">
            <div class="label">Mensaje</div>
            <div class="message-box">{{ $data['mensaje'] }}</div>
        </div>
    </div>
    <div class="footer">ATSA Tucumán — Sistema de contacto web</div>
</div>
</body>
</html>
