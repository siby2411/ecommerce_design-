<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

// Récupérer TOUS les produits avec leurs catégories en une seule requête
$stmt = $pdo->query("
    SELECT p.*, c.nom AS categorie_nom,
    (SELECT COUNT(*) FROM produits_images WHERE id_produit = p.id) AS nb_images
    FROM produits p
    LEFT JOIN categories c ON c.id = p.id_categorie
    ORDER BY c.nom, p.nom
");
$produits = $stmt->fetchAll();

// Grouper par catégorie
$groupes = [];
foreach ($produits as $p) {
    $cat = $p['categorie_nom'] ?? 'Sans catégorie';
    if (!isset($groupes[$cat])) {
        $groupes[$cat] = [];
    }
    $groupes[$cat][] = $p;
}

// Fonction pour afficher une carte avec mini-carrousel
function renderProductCard($p) {
    $images = getProductImages($GLOBALS['pdo'], $p['id']);
    $imageCount = count($images);

    $html = '<div class="product-card">';
    $html .= '<div class="image-wrapper">';

    if ($imageCount > 0) {
        $html .= '<div class="mini-carousel" data-index="0">';
        foreach ($images as $idx => $img) {
            $active = $idx === 0 ? 'active' : '';
            $html .= '<div class="mini-slide ' . $active . '">';
            $html .= '<img src="' . UPLOAD_URL . $img['image'] . '" alt="' . clean($p['nom']) . '" loading="lazy">';
            $html .= '</div>';
        }
        if ($imageCount > 1) {
            $html .= '<button class="carousel-prev" onclick="changeSlide(this, -1)">◀</button>';
            $html .= '<button class="carousel-next" onclick="changeSlide(this, 1)">▶</button>';
            $html .= '<div class="slide-indicators">';
            for ($i = 0; $i < $imageCount; $i++) {
                $active = $i === 0 ? 'active' : '';
                $html .= '<span class="dot ' . $active . '" onclick="goToSlide(this, ' . $i . ')"></span>';
            }
            $html .= '</div>';
        }
        $html .= '</div>';
    } else {
        $html .= '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#aaa;font-size:3rem;">📦</div>';
    }

    $html .= ($p['stock'] > 0) ? '<span class="badge-stock">✓ En stock</span>' : '<span class="badge-stock out">✗ Rupture</span>';
    $html .= '</div>';

    $html .= '<div class="body">';
    $html .= '<div class="name">' . clean($p['nom']) . '</div>';
    $html .= '<div class="prix">' . formatPrix($p['prix']) . '</div>';
    $html .= '<div class="actions">';
    $html .= '<button class="btn-acheter" ' . ($p['stock'] <= 0 ? 'disabled' : '') . '>
                <i class="fas fa-shopping-cart"></i> Ajouter
             </button>';
    $html .= '<a href="produits/detail.php?id=' . $p['id'] . '" class="btn-detail"><i class="fas fa-eye"></i></a>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    return $html;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue | OMEGA INFORMATIQUE CONSULTING</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #0f1a30; --secondary: #2563eb; --gold: #d4af37; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; color: #1a1a2e; }
        .header {
            background: linear-gradient(135deg, #0f0c29, #302b63);
            padding: 20px 0;
            position: sticky; top: 0; z-index: 1000;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        .header .brand {
            display: flex; align-items: center; gap: 15px;
            color: #fff; text-decoration: none;
        }
        .header .brand .logo {
            width: 50px; height: 50px;
            background: linear-gradient(135deg, var(--gold), #b8862b);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 20px; color: #0f1a30;
        }
        .header .brand h1 { font-size: 1.8rem; font-weight: 700; margin: 0; }
        .header .brand h1 span { color: var(--gold); }
        .header .brand small { display: block; font-size: 0.8rem; color: #aaa; font-weight: 300; }

        /* Bandeau publicitaire */
        .banner {
            background: linear-gradient(135deg, #1a1a2e, #2d2d44);
            padding: 20px 0;
            margin-bottom: 30px;
            border: 2px solid var(--gold);
            border-radius: 12px;
            overflow: hidden;
            position: relative;
        }
        .banner .banner-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
            padding: 0 20px;
        }
        .banner .banner-content img {
            max-height: 120px;
            border-radius: 10px;
            border: 3px solid var(--gold);
            box-shadow: 0 4px 20px rgba(212, 175, 55, 0.3);
            transition: transform 0.3s;
        }
        .banner .banner-content img:hover {
            transform: scale(1.05);
        }
        .banner .banner-text {
            color: #fff;
            text-align: center;
        }
        .banner .banner-text h3 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gold);
            margin: 0;
        }
        .banner .banner-text p {
            color: #ccc;
            margin: 5px 0 0 0;
            font-size: 1.1rem;
        }
        .banner .banner-text .promo {
            display: inline-block;
            background: #dc2626;
            color: #fff;
            padding: 4px 20px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 1rem;
            margin-top: 8px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .hero {
            background: linear-gradient(135deg, var(--primary), #1c2c52);
            color: #fff; padding: 40px 0; text-align: center; margin-bottom: 30px;
        }
        .hero h2 { font-size: 2.5rem; font-weight: 300; }
        .hero h2 span { font-weight: 700; color: var(--gold); }
        .hero p { font-size: 1.1rem; color: #b0b0d0; max-width: 500px; margin: 0 auto; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

        .category-section {
            background: #fff; border-radius: 16px; padding: 25px;
            margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .category-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px; padding-bottom: 15px;
            border-bottom: 3px solid var(--gold);
        }
        .category-header h3 {
            font-size: 1.6rem; font-weight: 700; color: var(--primary);
            display: flex; align-items: center; gap: 12px;
        }
        .category-header .count {
            background: var(--primary); color: #fff;
            padding: 2px 14px; border-radius: 20px;
            font-size: 0.85rem; font-weight: 600;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }
        .product-card {
            background: #fff; border-radius: 12px; overflow: hidden;
            border: 1px solid #e5e9f2; transition: all 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-color: var(--gold);
        }
        .image-wrapper {
            height: 200px; background: #f8f9fa;
            position: relative; overflow: hidden;
            display: flex; align-items: center; justify-content: center;
        }
        .image-wrapper .badge-stock {
            position: absolute; top: 8px; right: 8px;
            background: #16a34a; color: #fff;
            padding: 3px 10px; border-radius: 20px;
            font-size: 0.65rem; font-weight: 700;
            z-index: 10;
        }
        .image-wrapper .badge-stock.out { background: #dc2626; }
        .body { padding: 15px; }
        .body .name {
            font-size: 0.95rem; font-weight: 700; color: var(--primary);
            height: 42px; overflow: hidden;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
        }
        .body .prix {
            font-size: 1.3rem; font-weight: 800; color: var(--secondary);
            margin: 8px 0;
        }
        .body .actions { display: flex; gap: 6px; }
        .body .btn-acheter {
            flex: 1; padding: 8px 12px; border: none; border-radius: 6px;
            background: linear-gradient(135deg, var(--secondary), #1d4fd0);
            color: #fff; font-weight: 600; font-size: 0.85rem;
            cursor: pointer; transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 6px;
        }
        .body .btn-acheter:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(37,99,235,0.3);
        }
        .body .btn-acheter:disabled { opacity: 0.5; cursor: not-allowed; }
        .body .btn-detail {
            padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px;
            background: #fff; color: #555; cursor: pointer;
            transition: all 0.2s; text-decoration: none;
            display: inline-flex; align-items: center;
        }
        .body .btn-detail:hover { background: #f0f2f5; }
        .footer {
            background: var(--primary); color: #aaa;
            padding: 20px 0; text-align: center; margin-top: 30px;
        }
        .footer .brand { font-size: 1.2rem; font-weight: 700; color: var(--gold); }

        /* Mini-carrousel */
        .mini-carousel {
            width: 100%; height: 100%;
            position: relative; overflow: hidden;
        }
        .mini-slide {
            position: absolute; top: 0; left: 0;
            width: 100%; height: 100%;
            opacity: 0; transition: opacity 0.5s ease;
            display: flex; align-items: center; justify-content: center;
        }
        .mini-slide.active { opacity: 1; z-index: 1; }
        .mini-slide img { width: 100%; height: 100%; object-fit: contain; padding: 10px; }
        .mini-carousel .carousel-prev,
        .mini-carousel .carousel-next {
            position: absolute; top: 50%; transform: translateY(-50%);
            background: rgba(0,0,0,0.5); color: #fff;
            border: none; padding: 5px 8px; border-radius: 50%;
            cursor: pointer; z-index: 20;
            font-size: 12px; transition: background 0.3s;
        }
        .mini-carousel .carousel-prev:hover,
        .mini-carousel .carousel-next:hover {
            background: rgba(0,0,0,0.8);
        }
        .mini-carousel .carousel-prev { left: 5px; }
        .mini-carousel .carousel-next { right: 5px; }
        .mini-carousel .slide-indicators {
            position: absolute; bottom: 8px; left: 50%;
            transform: translateX(-50%);
            display: flex; gap: 4px; z-index: 20;
        }
        .mini-carousel .slide-indicators .dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: rgba(255,255,255,0.5);
            cursor: pointer; transition: all 0.3s;
        }
        .mini-carousel .slide-indicators .dot.active {
            background: #fff;
            transform: scale(1.2);
        }
        @media (max-width: 768px) {
            .hero h2 { font-size: 1.8rem; }
            .product-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); }
            .category-header { flex-direction: column; align-items: flex-start; gap: 8px; }
            .category-header h3 { font-size: 1.3rem; }
            .header .brand h1 { font-size: 1.2rem; }
            .image-wrapper { height: 150px; }
            .banner .banner-content { flex-direction: column; gap: 15px; }
            .banner .banner-content img { max-height: 80px; }
            .banner .banner-text h3 { font-size: 1.5rem; }
        }
    </style>
</head>
<body>

<header class="header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <a href="catalogue.php" class="brand">
                <div class="logo">OI</div>
                <div>
                    <h1>OMEGA <span>INFORMATIQUE</span></h1>
                    <small>Matériel informatique & solutions IT</small>
                </div>
            </a>
            <div class="d-flex align-items-center gap-3">
                <a href="catalogue.php" class="text-white text-decoration-none">
                    <i class="fas fa-home"></i>
                </a>
                <a href="login.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-user"></i> Admin
                </a>
            </div>
        </div>
    </div>
</header>

<section class="hero">
    <div class="container">
        <h2>Découvrez notre <span>catalogue</span></h2>
        <p>Matériel informatique de haute qualité</p>
    </div>
</section>

<div class="container">
    <!-- ================================================= -->
    <!-- BANDEAU PUBLICITAIRE AVEC L'IMAGE OK.JPEG          -->
    <!-- ================================================= -->
    <div class="banner">
        <div class="banner-content">
            <img src="ok.jpeg" alt="Promotion OMEGA" loading="lazy">
            <div class="banner-text">
                <h3>🔥 OFFRE SPÉCIALE</h3>
                <p>Découvrez nos produits en promotion</p>
                <span class="promo">- 30%</span>
            </div>
        </div>
    </div>

    <?php if (empty($groupes)): ?>
        <div class="alert alert-warning text-center py-5">
            <i class="fas fa-box" style="font-size:3rem;"></i>
            <h4 class="mt-3">Aucun produit disponible</h4>
        </div>
    <?php else: ?>
        <?php foreach ($groupes as $categorie => $items): ?>
            <div class="category-section">
                <div class="category-header">
                    <h3>
                        <i class="fas fa-tag" style="color:var(--gold);"></i>
                        <?= clean($categorie) ?>
                        <span class="count"><?= count($items) ?></span>
                    </h3>
                    <a href="#top" class="text-decoration-none" style="color:var(--secondary);font-weight:600;font-size:0.9rem;">
                        <i class="fas fa-arrow-up"></i> Haut
                    </a>
                </div>
                <div class="product-grid">
                    <?php foreach ($items as $p): ?>
                        <?= renderProductCard($p) ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<footer class="footer">
    <div class="container">
        <div class="brand">OMEGA INFORMATIQUE CONSULTING</div>
        <p style="font-size:0.85rem;">&copy; <?= date('Y') ?> Tous droits réservés</p>
    </div>
</footer>

<script>
function changeSlide(btn, direction) {
    const carousel = btn.closest('.mini-carousel');
    const slides = carousel.querySelectorAll('.mini-slide');
    const dots = carousel.querySelectorAll('.dot');
    let current = parseInt(carousel.dataset.index) || 0;
    current = (current + direction + slides.length) % slides.length;
    carousel.dataset.index = current;
    slides.forEach((s, i) => s.classList.toggle('active', i === current));
    dots.forEach((d, i) => d.classList.toggle('active', i === current));
}

function goToSlide(dot, index) {
    const carousel = dot.closest('.mini-carousel');
    const slides = carousel.querySelectorAll('.mini-slide');
    const dots = carousel.querySelectorAll('.dot');
    carousel.dataset.index = index;
    slides.forEach((s, i) => s.classList.toggle('active', i === index));
    dots.forEach((d, i) => d.classList.toggle('active', i === index));
}

document.addEventListener('DOMContentLoaded', function() {
    // Auto-play des carrousels
    document.querySelectorAll('.mini-carousel').forEach(carousel => {
        if (carousel.querySelectorAll('.mini-slide').length > 1) {
            setInterval(() => {
                const btn = carousel.querySelector('.carousel-next');
                if (btn) changeSlide(btn, 1);
            }, 4000);
        }
    });

    // Bouton Ajouter
    document.querySelectorAll('.btn-acheter').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!this.disabled) {
                const original = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check"></i> Ajouté !';
                this.style.background = '#16a34a';
                setTimeout(() => {
                    this.innerHTML = original;
                    this.style.background = '';
                }, 2000);
            }
        });
    });
});
</script>
</body>
</html>
