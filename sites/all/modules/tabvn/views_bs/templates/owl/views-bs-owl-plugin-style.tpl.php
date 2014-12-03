<?php if (!empty($rows)): ?>
  <div class="views-bs-owl-slider-wrapper" id="views-bs-owl-<?php print $element_id; ?>">
    <?php foreach ($rows as $key => $row): ?>
      <div class="views-bs-owl-slider-item views-bs-owl-slider-item-<?php print $key; ?>"><?php print $row; ?></div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>