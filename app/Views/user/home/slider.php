<!-- Hero Section -->
<section id="hero" class="hero section">
    <div id="hero-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">
            <?php foreach($slider as $key => $s): ?>
                <div class="carousel-item <?= $key === 0 ? 'active' : '' ?>">
                    <div class="hero-container">
                        <img src="<?= base_url('assets/img/' . $s['gambar']) ?>" class="d-block" alt="Slider Image">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
        </a>

        <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
        </a>

        <ol class="carousel-indicators"></ol>
    </div>
</section><!-- /Hero Section -->