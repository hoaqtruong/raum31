<div id="wrapper">
  <!-- BEGIN HEADER -->
  <header id="home-wrapper">
    <nav class="navbar navbar-default" role="navigation">
      <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <?php if ($page['main_navigation']): ?>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
              <span class="sr-only"><?php print t('Toggle navigation'); ?></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          <?php endif; ?>
          <?php
          $front_page_link = $front_page;
		  $site_name_class = 'site-name-class';
          if (drupal_is_front_page()) {
            $front_page_link = '#home';
			$site_name_class = 'scrollto';
          }
          ?>
          <?php if ($logo): ?>
            <!-- BEGIN LOGO -->
            <a class="navbar-brand <?php print $site_name_class; ?>" href="<?php print $front_page_link; ?>">
              <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>">
            </a>
            <!-- END LOGO -->
          <?php endif; ?>
          <?php if ($site_name): ?>
            <h1><a href="<?php print $front_page_link; ?>" class="navbar-brand <?php print $site_name_class; ?>"><?php print $site_name; ?></a></h1>
          <?php endif; ?>
        </div>
        <?php if ($page['main_navigation']): ?>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="main-navigation collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <!-- BEGIN NAVIGATION -->
            <?php print render($page['main_navigation']); ?>
            <!-- END NAVIGATION -->
          </div><!-- /.navbar-collapse -->
        <?php endif; ?>
      </div><!-- /.container-fluid -->
    </nav>
  </header>
  <!-- END HEADER -->

  <?php $content_area_class = 'no-margin-top'; ?>
  <?php if (!empty($title) || !empty($breadcrumb)): ?>
    <?php $content_area_class = "margin-top"; ?>
  <?php endif; ?>
  <div id="page-builder-container" class="page-builder-wrapper <?php print $content_area_class; ?>">
    <?php if (!empty($title) || !empty($breadcrumb)): ?>

      <div id="page-header-wrapper" class="main-header clearfix">
        <div class="page-header-inner">
          <?php if (!empty($title)): ?>
            <h1 id="page-title" class="page-title red text-center"><?php print $title; ?></h1>
          <?php endif; ?>
          <?php
          if (theme_get_setting('use_default_breadcrumb') && !empty($breadcrumb)) {
            print $breadcrumb;
          } else {
            if ($page['custom_breadcrumb']) {
              print '<div class="breadcrumb text-center">' . render($page['custom_breadcrumb']) . '</div>';
            }
          }
          ?>


        </div>
      </div>
    <?php endif; ?>

    <?php if (!empty($page['highlighted'])): ?>
      <div class="highlighted jumbotron"><?php print render($page['highlighted']); ?></div>
    <?php endif; ?>
    <a id="main-content"></a>
    <?php print render($title_prefix); ?>

    <?php print render($title_suffix); ?>
    <?php print $messages; ?>
    <?php if (!empty($tabs)): ?>
      <?php print render($tabs); ?>
    <?php endif; ?>
    <?php if (!empty($page['help'])): ?>
      <?php print render($page['help']); ?>
    <?php endif; ?>
    <?php if (!empty($action_links)): ?>
      <ul class="action-links"><?php print render($action_links); ?></ul>
    <?php endif; ?>
    <?php print render($page['content']); ?>
  </div>

  <?php if ($page['footer']): ?>
    <!-- BEGIN FOOTER -->
    <footer>
      <div class="container">
        <?php print render($page['footer']); ?>
      </div>
    </footer>        
    <!-- END FOOTER -->
  <?php endif; ?>

  <!-- BEGIN GO TO TOP --><a href="#." class="back-to-top" id="back-to-top"><i class="fa fa-angle-up"></i></a><!-- END GO TO TOP -->
</div>