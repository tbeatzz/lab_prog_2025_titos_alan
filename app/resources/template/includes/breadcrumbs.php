<?php if (!empty($this->breadcrumbs)): ?>
    <section class="mb-4 d-flex flex-row container">
        <div class="breadCrumb border rounded p-2 shadow-sm w-100">
            <span class="d-flex flex-row gap-2">
                <?php foreach ($this->breadcrumbs as $index => $crumb): ?>
                    <?php if (!empty($crumb['url']) && $index !== array_key_last($this->breadcrumbs)): ?>
                        <a href="<?= $crumb['url'] ?>" class="nav-link"><?= $crumb['label'] ?></a>
                        <span>/</span>
                    <?php else: ?>
                        <span><?= $crumb['label'] ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </span>
        </div>
    </section>
<?php endif; ?>
