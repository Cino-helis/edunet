<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe - EduNet</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .content {
            padding: 40px 30px;
        }
        h1 {
            color: #1f2937;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            color: white;
            padding: 16px 32px;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            margin: 20px 0;
        }
        .info-box {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 16px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .footer {
            background: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            font-size: 14px;
            color: #9ca3af;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L3 7L12 12L21 7L12 2Z" fill="#3b82f6"/>
                    <path d="M3 17L12 22L21 17V11L12 16L3 11V17Z" fill="#1e40af"/>
                </svg>
            </div>
            <h1 style="color: white; margin: 0;">EduNet</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <h1>Réinitialisation de mot de passe</h1>
            
            <p>Bonjour,</p>
            
            <p>
                Vous recevez cet email car nous avons reçu une demande de réinitialisation 
                de mot de passe pour votre compte EduNet.
            </p>

            <div style="text-align: center;">
                <a href="{{ $url }}" class="button">
                    Réinitialiser mon mot de passe
                </a>
            </div>

            <div class="info-box">
                <p style="margin: 0; color: #1e40af; font-weight: 500;">
                    ⏱️ Ce lien expirera dans {{ config('auth.passwords.users.expire') }} minutes.
                </p>
            </div>

            <p>
                Si vous n'avez pas demandé de réinitialisation de mot de passe, 
                aucune action n'est requise de votre part.
            </p>

            <p style="font-size: 14px; color: #9ca3af; margin-top: 30px;">
                Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :
                <br>
                <a href="{{ $url }}" style="color: #3b82f6; word-break: break-all;">{{ $url }}</a>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>EduNet</strong> - Système de gestion des notes</p>
            <p>© 2024 EduNet. Tous droits réservés.</p>
            <p>
                <a href="mailto:support@edunet.com" style="color: #3b82f6; text-decoration: none;">support@edunet.com</a>
            </p>
        </div>
    </div>
</body>
</html>