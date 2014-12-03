<!DOCTYPE html>
<html lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php print $head; ?>
    <title><?php print $head_title; ?></title>
	 <?php print $styles; ?>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="<?php print base_path() . path_to_theme(); ?>/js/html5shiv.js"></script>
    <script src="<?php print base_path() . path_to_theme(); ?>/js/respond.min.js"></script>
    <![endif]-->
    <?php print $scripts; ?>
  </head>
  <?php $classes .= ' fixed-header'; ?>
  <body class="<?php print $classes; ?>" <?php print $attributes; ?> data-spy="scroll" data-target=".navbar">
    <div id="skip-link">
      <a href="#main-content" class="element-invisible element-focusable"><?php print t('Skip to main content'); ?></a>
    </div>
    <?php print $page_top; ?>
    <?php print $page; ?>
    <?php print $page_bottom; ?>
  </body>
</html>
