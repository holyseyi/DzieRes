<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | DzieRes</title>
    <link href="<?= \asset('vendor/bootstrap/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= \asset('vendor/fontawesome/all.min.css') ?>" rel="stylesheet">
    <link href="<?= \asset('vendor/fonts/local-fonts.css') ?>" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #0f0f1a; color: white; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .error-container { text-align: center; padding: 40px 20px; }
        .error-code { font-family: 'Playfair Display', serif; font-size: 12rem; font-weight: 700; background: linear-gradient(135deg, #001a4a, #003380); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1; margin-bottom: 0; }
        .error-title { font-family: 'Playfair Display', serif; font-size: 2rem; margin-bottom: 1rem; }
        .error-text { color: rgba(255,255,255,0.6); margin-bottom: 2rem; font-size: 1.1rem; }
        .btn-gold { background: linear-gradient(135deg, #001a4a, #003380); color: #fff; border: none; padding: 12px 30px; border-radius: 50px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; }
        .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 4px 20px rgba(0,26,74,0.3); color: #fff; }
        @media (max-width: 768px) { .error-code { font-size: 8rem; } .error-title { font-size: 1.5rem; } }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <h1 class="error-title">Page Not Found</h1>
        <p class="error-text">The page you're looking for doesn't exist or has been moved.</p>
        <div class="d-flex gap-3 justify-content-center">
            <a href="<?= \baseUrl() ?>" class="btn-gold"><?= \icon('home', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i>Go Home</a>
            <a href="<?= \baseUrl('menu') ?>" class="btn-gold" style="background: transparent; border: 2px solid #001a4a; -webkit-text-fill-color: #001a4a;"><?= \icon('utensils', ['style' => 'width:0.9em;height:0.9em;margin-right:0.35rem;vertical-align:-0.15em;']) ?>></i>View Menu</a>
        </div>
    </div>
</body>
</html>