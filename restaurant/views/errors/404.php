<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | DzieRes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #0f0f1a; color: white; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .error-container { text-align: center; padding: 40px 20px; }
        .error-code { font-family: 'Playfair Display', serif; font-size: 12rem; font-weight: 700; background: linear-gradient(135deg, #c9a84c, #e8c96a); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1; margin-bottom: 0; }
        .error-title { font-family: 'Playfair Display', serif; font-size: 2rem; margin-bottom: 1rem; }
        .error-text { color: rgba(255,255,255,0.6); margin-bottom: 2rem; font-size: 1.1rem; }
        .btn-gold { background: linear-gradient(135deg, #c9a84c, #e8c96a); color: #1a1a2e; border: none; padding: 12px 30px; border-radius: 50px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; }
        .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 4px 20px rgba(201,168,76,0.3); color: #1a1a2e; }
        @media (max-width: 768px) { .error-code { font-size: 8rem; } .error-title { font-size: 1.5rem; } }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <h1 class="error-title">Page Not Found</h1>
        <p class="error-text">The page you're looking for doesn't exist or has been moved.</p>
        <div class="d-flex gap-3 justify-content-center">
            <a href="<?= \baseUrl() ?>" class="btn-gold"><i class="fas fa-home me-1"></i>Go Home</a>
            <a href="<?= \baseUrl('menu') ?>" class="btn-gold" style="background: transparent; border: 2px solid #c9a84c; -webkit-text-fill-color: #c9a84c;"><i class="fas fa-utensils me-1"></i>View Menu</a>
        </div>
    </div>
</body>
</html>