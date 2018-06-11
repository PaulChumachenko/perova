<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title; if (isset($_GET['page']) && isset($text_page)) { echo " - ". ((int) $_GET['page'])." ".$text_page;} ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; if (isset($_GET['page']) && isset($text_page)) { echo " - ". ((int) $_GET['page'])." ".$text_page;} ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<meta property="og:title" content="<?php echo $title; if (isset($_GET['page']) && isset($text_page)) { echo " - ". ((int) $_GET['page'])." ".$text_page;} ?>" />
<meta property="og:type" content="website" />
<?php if (isset($og_url)) { ?>
<meta property="og:url" content="<?php echo $og_url; ?>" />
<?php } ?>
<?php if (isset($og_image) && $og_image) { ?>
<meta property="og:image" content="<?php echo $og_image; ?>" />
<?php } else { ?>
<meta property="og:image" content="<?php echo $logo; ?>" />
<?php } ?>
<meta property="og:site_name" content="<?php echo $name; ?>" />
<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<script src="catalog/view/theme/coloring/assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<?php if (!empty($stylesheet)) { ?>
<link href="catalog/view/theme/coloring/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<link href="catalog/view/theme/coloring/assets/font-awesome-4.4.0/css/font-awesome.min.css" rel="stylesheet" >
<link href="catalog/view/theme/coloring/stylesheet/stylesheet.css" rel="stylesheet">
<?php } ?>
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<script src="catalog/view/theme/coloring/assets/jquery.dotdotdot.min.js" type="text/javascript"></script>
<script src="catalog/view/theme/coloring/assets/common.js" type="text/javascript"></script>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>

</head>
<body class="<?php echo $class; ?>">
<header>
	<div id="top">
		<div class="container text-center text-right-md" >
			<div class="pull-left">
				<div class="inline-block">
					<?php echo $language; ?>
				</div>
				<div class="inline-block">
					<?php echo $currency; ?>
				</div>
			</div>

			</div>
			<?php if ($help_menu_toggle) { ?>
			<div class="pull-right <?php echo $help_menu_left; ?>">
				<div class="btn-group">
					<button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-support icon"></i><span class="hidden-sm hidden-xs">&nbsp;&nbsp;<?php echo $help_menu_text; ?>&nbsp;</span>&nbsp;<span class="fa fa fa-angle-down caretalt"></span>
					</button>
					<ul class="dropdown-menu <?php if (!$help_menu_left){echo 'pull-right';} ?>">
						<?php foreach ($help_menu as $item) { ?>
						<li><a href="<?php echo $item['link'][$language_id]; ?>"><?php echo html_entity_decode($item['title'][$language_id], ENT_QUOTES, 'UTF-8'); ?></a></li>
						<?php } ?>
					</ul>
				</div>
			</div>
			<?php }?>

		</div>
	</div>
  <div class="container">
    <div class="row logo-line">
      <div class="col-sm-12 col-md-3">
        <div id="logo">
          <?php if ($logo) { ?>
						<?php if (isset($og_url) && ($home == $og_url)) { ?>
							<img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" />
						<?php } else { ?>
							<a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
						<?php } ?>
          <?php } else { ?>
					<div class="btn-group btn-block">
						<button type="button" class="btn btn-link btn-block">
							<a href="<?php echo $home; ?>"><?php echo $name; ?></a>
						</button>
					</div>
          <?php } ?>
				</div>
      </div>
			<div class="col-sm-12 col-md-3 text-center text-left-md">
				<?php if ($header_contacts_toggle) { ?>
				<div id="phone">
					<div class="phone">
						<span data-toggle="dropdown" class="main-phone">
							<i class="glyphicon glyphicon-phone-alt icon"></i>&nbsp;
							<?php echo $main_telephone[$language_id]; ?>
							<?php if ($header_add_contacts_toggle) { ?>
							<span class="fa fa fa-angle-down caretalt"></span>
							<?php }?>
						</span>
						<?php if ($header_add_contacts_toggle) { ?>
						<ul class="dropdown-menu allcontacts">
							<?php if (!empty($all_phones)) { ?>
							<?php foreach ($all_phones as $item) { ?>
							<li>
								<a href="tel:<?php echo $item['title'][$language_id]; ?>">
									<?php if ($item['image']) { ?>
									<img src="image/<?php echo $item['image']; ?>" class="max16" />&nbsp;
									<?php } ?>
									<?php echo html_entity_decode($item['title'][$language_id], ENT_QUOTES, 'UTF-8'); ?>
								</a>
							</li>
							<?php } ?>
							<li class="divider"></li>
							<?php }?>
							<?php if ($contact_schedule[$language_id]) { ?>
							<li>
								<div class="schedule">
									<i class="fa fa-clock-o fu"></i>&nbsp;
									<?php echo html_entity_decode($contact_schedule[$language_id], ENT_QUOTES, 'UTF-8'); ?>
								</div>
							</li>
							<li class="divider"></li>
							<?php }?>
							<li>
								<?php if (!empty($contact_email[$language_id])) { ?>
								<a href="mailto:<?php echo $contact_email[$language_id]; ?>" target="_blank"><i class="fa fa-envelope-o fu"></i>&nbsp;
								<?php echo $contact_email[$language_id]; ?></a>
								<?php }?>

								<?php if (!empty($other_contacts)) { ?>
								<?php foreach ($other_contacts as $item) { ?>
								<a href="<?php echo $item['link'][$language_id]; ?>" target="_blank">
									<?php if ($item['image']) { ?>
									<img src="image/<?php echo $item['image']; ?>" class="max16" />&nbsp;
									<?php } ?>
									<?php echo html_entity_decode($item['title'][$language_id], ENT_QUOTES, 'UTF-8'); ?>
								</a>
								<?php } ?>
								<?php }?>
							</li>
						</ul>
						<?php }?>
					</div>
					<br>
					<span class="hint"><?php echo $contact_hint[$language_id]; ?></span>
				</div>
				<?php }?>
			</div>
			<div class="col-sm-12 col-md-6 text-center text-right-md">
				<div id="header-menu">
				<?php if ($header_menu_toggle) { ?>
				<?php foreach ($header_menu as $item) { ?>
				<a class="btn" href="<?php echo $item['link'][$language_id]; ?>"><span><?php echo html_entity_decode($item['title'][$language_id], ENT_QUOTES, 'UTF-8'); ?></span></a>
				<?php } ?>
				<?php }?>
				</div>
			</div>
    </div>
  </div>
	<div class="container">
		<div class="row menu-line">
			<div class="col-sm-12 col-md-7 col-md-push-3 search-box"><?php echo $search; ?></div>
			<div class="col-sm-6 col-sm-push-6 col-md-2 col-md-push-3 cart-box"><?php echo $cart; ?></div>
			<div class="col-sm-6 col-sm-pull-6 col-md-3 col-md-pull-9 menu-box">
				<?php if ($categories) { ?>
				<nav id="menu" class="btn-group btn-block">
					<button type="button" class="btn btn-danger btn-block dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-bars"></i>
						<?php echo $text_category; ?>
					</button>
					<ul id="menu-list" class="dropdown-menu">
						<?php foreach ($categories as $category) { ?>
						<?php if ($category['children']) { ?>
						<li>
							<span class="toggle-child">
								<i class="fa fa-plus plus"></i>
								<i class="fa fa-minus minus"></i>
							</span>
							<a class="with-child" href="<?php echo $category['href']; ?>">
								<i class="fa fa-angle-right arrow"></i>
								<?php echo $category['name']; ?>
							</a>
							<?php if ($category['column'] < 2) { ?>
								<?php	$col_class = 'col-md-12'; ?>
								<?php	$box_class = 'box-col-1'; ?>
								<?php	$cols_count = 1; ?>
							<?php } elseif ($category['column'] == 2) { ?>
								<?php	$col_class = 'col-md-6'; ?>
								<?php	$box_class = 'box-col-2'; ?>
								<?php	$cols_count = 2; ?>
							<?php } else { ?>
								<?php	$col_class = 'col-md-4'; ?>
								<?php	$box_class = 'box-col-3'; ?>
								<?php	$cols_count = 3; ?>
							<?php } ?>
							<div class="child-box <?php echo $box_class; ?>">
								<div class="row">
								<?php $i = 0; ?>
								<?php foreach ($category['children'] as $child) { ?>
								<div class="<?php echo $col_class; ?>">
									<div class="child-box-cell">
										<h5>
										<?php if($child['children2']) { ?>
										<span class="toggle-child2">
											<i class="fa fa-plus plus"></i>
											<i class="fa fa-minus minus"></i>
										</span>
										<?php } ?>
										<a href="<?php echo $child['href']; ?>" class="<?php if($child['children2']) {echo 'with-child2';}?>"><span class="livel-down visible-xs-inline">&#8627;</span><?php echo $child['name']; ?></a></h5>
										<?php if($child['children2']) { ?>
										<ul class="child2-box">
										<?php foreach ($child['children2'] as $child2) { ?>
											<li><a href="<?php echo $child2['href']; ?>"><span class="livel-down">&#8627;</span><?php echo $child2['name']; ?></a></li>
										<?php } ?>
										</ul>
										<?php } ?>
									</div>
								</div>
									<?php $i++; ?>
									<?php if (($i == $cols_count) &($i != 1)) { ?>
									<div class="clearfix visible-md visible-lg"></div>
									<?php $i = 0; ?>
									<?php } ?>
								<?php } ?>
								</div>
							</div>
						</li>
						<?php } else { ?>
						<li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
						<?php } ?>
						<?php } ?>
						<?php if ($add_category_menu_toggle) { ?>
						<?php foreach ($add_category_menu as $item) { ?>
						<li><a href="<?php echo $item['link'][$language_id]; ?>"><?php echo html_entity_decode($item['title'][$language_id], ENT_QUOTES, 'UTF-8'); ?></a></li>
						<?php } ?>
						<?php }?>
					</ul>
					<?php if ($category_mask) { ?>
					<div id="menuMask"></div>
					<script>$('#menu-list').hover(function () {$('body').addClass('blured')},function () {$('body').removeClass('blured')});</script>
					<?php }?>
				</nav>
				<?php } ?>
			</div>
		</div>
	</div>
</header>
<div class="content-wrapper">