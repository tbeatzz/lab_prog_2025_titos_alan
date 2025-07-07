<?php if (!empty($this->breadcrumbs)): ?>
    <!-- Sección del breadcrumb solo si hay elementos -->
    <section class="mb-4 d-flex flex-row container">
        <div class="breadCrumb border rounded p-2 shadow-sm w-100">
            <span class="d-flex flex-row gap-2">
                
                <?php foreach ($this->breadcrumbs as $index => $crumb): ?>
                    <?php
                    // Si el breadcrumb tiene una URL y no es el último elemento
                    if (!empty($crumb['url']) && $index !== array_key_last($this->breadcrumbs)): ?>
                        
                        <!-- Breadcrumb con enlace -->
                        <a href="<?= htmlspecialchars($crumb['url']) ?>" class="nav-link">
                            <?= htmlspecialchars($crumb['label']) ?>
                        </a>
                        <span>/</span>
                        
                    <?php else: ?>
                        <!-- Último breadcrumb (activo), sin enlace -->
                        <span><?= htmlspecialchars($crumb['label']) ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>

            </span>
        </div>
    </section>
<?php endif; ?>
