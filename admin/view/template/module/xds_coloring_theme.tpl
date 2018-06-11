<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right" id="control-buttons">
				<a onclick="apply()" class="btn btn-success" data-toggle="tooltip" title="Применить" data-placement="bottom"><i class="fa fa-check"></i></a>
        <button type="submit" form="form-xds-coloring-theme" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary" data-placement="bottom"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-warning" data-placement="bottom"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default alert-helper">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-xds-coloring-theme" class="form-horizontal">

					<ul class="nav nav-pills">
						<li class="active"><a href="#tab-main-menu" data-toggle="pill">Главное меню</a></li>
						<li><a href="#tab-help-menu" data-toggle="pill">Меню помощи</a></li>
						<li><a href="#tab-header-category-menu" data-toggle="pill">Меню категорий</a></li>
						<li><a href="#tab-header-contacts" data-toggle="pill">Контакты в шапке</a></li>
						<li><a href="#tab-home-carousel" data-toggle="pill">Карусель на главной</a></li>
						<li><a href="#tab-footer-map" data-toggle="pill">Карта в подвале</a></li>
						<li><a href="#tab-pay-icons" data-toggle="pill">Иконки платежных систем</a></li>
					</ul>

					<hr>

					<div class="tab-content">
						<div class="tab-pane active" id="tab-main-menu">
							<div class="form-group">
                <label class="col-sm-2 control-label">Включить</label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <input type="radio" name="xds_coloring_theme_main_menu_toggle" value="1" <?php if ($xds_coloring_theme_main_menu_toggle) { echo 'checked'; } ?> /> Да
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="xds_coloring_theme_main_menu_toggle" value="0" <?php if (!$xds_coloring_theme_main_menu_toggle) { echo 'checked';} ?> /> Нет
                  </label>
                </div>
              </div>
							<hr>
							<div class="form-group">
								<label class="col-sm-2 control-label">Пункты меню</label>
								<div class="col-sm-10">
									<table id="main-menu-items" class="table table-bordered">
										<thead>
											<tr>
												<td class="nowrap"><i class="fa fa-edit fa14"></i>&nbsp;&nbsp;Текст</td>
												<td class="nowrap"><i class="fa fa-link fa14"></i>&nbsp;&nbsp;Ссылка</td>
												<td class="nowrap"><i class="fa fa-sort fa14"></i>&nbsp;&nbsp;Сортировка</td>
												<td></td>
											</tr>
										</thead>
										<tbody>
										<?php $item_row_main = 0; ?>
										<?php foreach ($xds_coloring_theme_main_menu_items as $xds_coloring_theme_main_menu_item) { ?>
											<tr id="item-row-main<?php echo $item_row_main; ?>">
												<td class="text-left">
													<?php foreach ($languages as $language) { ?>
													<div class="input-group pull-left">
														<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
														<input class="form-control" type="text" name="xds_coloring_theme_main_menu_item[<?php echo $item_row_main; ?>][title][<?php echo $language['language_id']; ?>]" value="<?php echo $xds_coloring_theme_main_menu_item['title'][$language['language_id']]; ?>" />
													</div>
													<?php } ?>
												</td>
												<td class="text-left">
													<?php foreach ($languages as $language) { ?>
													<div class="input-group pull-left">
														<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
															<input class="form-control" type="text" name="xds_coloring_theme_main_menu_item[<?php echo $item_row_main; ?>][link][<?php echo $language['language_id']; ?>]" value="<?php echo $xds_coloring_theme_main_menu_item['link'][$language['language_id']]; ?>" />
													</div>
													<?php } ?>
												</td>
												<td class="text-left">
													<input  class="form-control" type="text" name="xds_coloring_theme_main_menu_item[<?php echo $item_row_main; ?>][sort]" value="<?php echo $xds_coloring_theme_main_menu_item['sort']; ?>" />
												</td>
												<td class="text-right">
													<a class="btn btn-danger" onclick="$('#item-row-main<?php echo $item_row_main; ?>').remove();" data-toggle="tooltip" title="Удалить"><i class="fa fa-trash-o"></i></a>
												</td>
											</tr>
										<?php $item_row_main++; ?>
										<?php } ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="3"></td>
												<td class="text-right"><a class="btn btn-primary" onclick="addItemMain();" data-toggle="tooltip" title="Добавить"><i class="fa fa-plus-circle"></i></a></td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-help-menu">
							<div class="form-group">
                <label class="col-sm-2 control-label">Включить</label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <input type="radio" name="xds_coloring_theme_help_menu_toggle" value="1" <?php if ($xds_coloring_theme_help_menu_toggle) { echo 'checked'; } ?> /> Да
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="xds_coloring_theme_help_menu_toggle" value="0" <?php if (!$xds_coloring_theme_help_menu_toggle) { echo 'checked';} ?> /> Нет
                  </label>
                </div>
              </div>
							<hr>
							<div class="form-group">
                <label class="col-sm-2 control-label">Сместить влево</label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <input type="radio" name="xds_coloring_theme_help_menu_left" value="1" <?php if ($xds_coloring_theme_help_menu_left) { echo 'checked'; } ?> /> Да
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="xds_coloring_theme_help_menu_left" value="0" <?php if (!$xds_coloring_theme_help_menu_left) { echo 'checked';} ?> /> Нет
                  </label>
                </div>
              </div>
							<hr>
							<div class="form-group">
								<label class="col-sm-2 control-label">Заголовок меню</label>
								<div class="col-sm-10">
									<?php foreach ($languages as $language) { ?>
									<div class="input-group">
										<span class="input-group-addon">
											<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
										</span>
										<input name="xds_coloring_theme_help_menu_text[<?php echo $language['language_id']; ?>]" value="<?php echo $xds_coloring_theme_help_menu_text[$language['language_id']]; ?>" class="form-control" />
									</div>
									<?php } ?>
								</div>
							</div>
							<hr>
							<div class="form-group">
								<label class="col-sm-2 control-label">Пункты меню</label>
								<div class="col-sm-10">
									<table id="help-menu-items" class="table table-bordered">
										<thead>
											<tr>
												<td class="nowrap"><i class="fa fa-edit fa14"></i>&nbsp;&nbsp;Текст</td>
												<td class="nowrap"><i class="fa fa-link fa14"></i>&nbsp;&nbsp;Ссылка</td>
												<td class="nowrap"><i class="fa fa-sort fa14"></i>&nbsp;&nbsp;Сортировка</td>
												<td></td>
											</tr>
										</thead>
										<tbody>
										<?php $item_row_help = 0; ?>
										<?php foreach ($xds_coloring_theme_help_menu_items as $xds_coloring_theme_help_menu_item) { ?>
											<tr id="item-row-help<?php echo $item_row_help; ?>">
												<td class="text-left">
													<?php foreach ($languages as $language) { ?>
													<div class="input-group pull-left">
														<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
														<input class="form-control" type="text" name="xds_coloring_theme_help_menu_item[<?php echo $item_row_help; ?>][title][<?php echo $language['language_id']; ?>]" value="<?php echo $xds_coloring_theme_help_menu_item['title'][$language['language_id']]; ?>" />
													</div>
													<?php } ?>
												</td>
												<td class="text-left">
													<?php foreach ($languages as $language) { ?>
													<div class="input-group pull-left">
														<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
															<input class="form-control" type="text" name="xds_coloring_theme_help_menu_item[<?php echo $item_row_help; ?>][link][<?php echo $language['language_id']; ?>]" value="<?php echo $xds_coloring_theme_help_menu_item['link'][$language['language_id']]; ?>" />
													</div>
													<?php } ?>
												</td>
												<td class="text-left">
													<input  class="form-control" type="text" name="xds_coloring_theme_help_menu_item[<?php echo $item_row_help; ?>][sort]" value="<?php echo $xds_coloring_theme_help_menu_item['sort']; ?>" />
												</td>
												<td class="text-right">
													<a class="btn btn-danger" onclick="$('#item-row-help<?php echo $item_row_help; ?>').remove();" data-toggle="tooltip" title="Удалить"><i class="fa fa-trash-o"></i></a>
												</td>
											</tr>
										<?php $item_row_help++; ?>
										<?php } ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="3"></td>
												<td class="text-right"><a class="btn btn-primary" onclick="addItemHelp();" data-toggle="tooltip" title="Добавить"><i class="fa fa-plus-circle"></i></a></td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
						
						<div class="tab-pane" id="tab-header-category-menu">
							<div class="form-group">
                <label class="col-sm-2 control-label">Включить дополнительные ссылки в меню категорий</label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <input type="radio" name="xds_coloring_theme_add_cat_links_toggle" value="1" <?php if ($xds_coloring_theme_add_cat_links_toggle) { echo 'checked'; } ?> /> Да
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="xds_coloring_theme_add_cat_links_toggle" value="0" <?php if (!$xds_coloring_theme_add_cat_links_toggle) { echo 'checked';} ?> /> Нет
                  </label>
                </div>
              </div>
							<hr>
							<div class="form-group">
								<label class="col-sm-2 control-label">Дополнительные ссылки в меню категорий</label>
								<div class="col-sm-10">
									<table id="category-links-items" class="table table-bordered">
										<thead>
											<tr>
												<td class="nowrap"><i class="fa fa-edit fa14"></i>&nbsp;&nbsp;Текст</td>
												<td class="nowrap"><i class="fa fa-link fa14"></i>&nbsp;&nbsp;Ссылка</td>
												<td class="nowrap"><i class="fa fa-sort fa14"></i>&nbsp;&nbsp;Сортировка</td>
												<td></td>
											</tr>
										</thead>
										<tbody>
										<?php $item_row_cat_links = 0; ?>
										<?php foreach ($xds_coloring_theme_add_cat_links_items as $xds_coloring_theme_add_cat_links_item) { ?>
											<tr id="item-row-cat-links<?php echo $item_row_cat_links; ?>">
												<td class="text-left">
													<?php foreach ($languages as $language) { ?>
													<div class="input-group pull-left">
														<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
														<input class="form-control" type="text" name="xds_coloring_theme_add_cat_links_item[<?php echo $item_row_cat_links; ?>][title][<?php echo $language['language_id']; ?>]" value="<?php echo $xds_coloring_theme_add_cat_links_item['title'][$language['language_id']]; ?>" />
													</div>
													<?php } ?>
												</td>
												<td class="text-left">
													<?php foreach ($languages as $language) { ?>
													<div class="input-group pull-left">
														<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
															<input class="form-control" type="text" name="xds_coloring_theme_add_cat_links_item[<?php echo $item_row_cat_links; ?>][link][<?php echo $language['language_id']; ?>]" value="<?php echo $xds_coloring_theme_add_cat_links_item['link'][$language['language_id']]; ?>" />
													</div>
													<?php } ?>
												</td>
												<td class="text-left">
													<input  class="form-control" type="text" name="xds_coloring_theme_add_cat_links_item[<?php echo $item_row_cat_links; ?>][sort]" value="<?php echo $xds_coloring_theme_add_cat_links_item['sort']; ?>" />
												</td>
												<td class="text-right">
													<a class="btn btn-danger" onclick="$('#item-row-cat-links<?php echo $item_row_cat_links; ?>').remove();" data-toggle="tooltip" title="Удалить"><i class="fa fa-trash-o"></i></a>
												</td>
											</tr>
										<?php $item_row_cat_links++; ?>
										<?php } ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="3"></td>
												<td class="text-right"><a class="btn btn-primary" onclick="addItemCatLinks();" data-toggle="tooltip" title="Добавить"><i class="fa fa-plus-circle"></i></a></td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
						
						<div class="tab-pane" id="tab-header-contacts">
							<div class="form-group">
                <label class="col-sm-2 control-label">Включить</label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <input type="radio" name="xds_coloring_theme_contact_main_toggle" value="1" <?php if ($xds_coloring_theme_contact_main_toggle) { echo 'checked'; } ?> /> Да
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="xds_coloring_theme_contact_main_toggle" value="0" <?php if (!$xds_coloring_theme_contact_main_toggle) { echo 'checked';} ?> /> Нет
                  </label>
                </div>
              </div>
							<hr>
							<div class="form-group">
								<label class="col-sm-2 control-label">Основной номер телефона</label>
								<div class="col-sm-10">
									<?php foreach ($languages as $language) { ?>
									<div class="input-group">
										<span class="input-group-addon">
											<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
										</span>
										<input name="xds_coloring_theme_contact_main_phone[<?php echo $language['language_id']; ?>]" value="<?php echo $xds_coloring_theme_contact_main_phone[$language['language_id']]; ?>" class="form-control" />
									</div>
									<?php } ?>
								</div>
							</div>
							<hr>
							<div class="form-group">
								<label class="col-sm-2 control-label">Подсказка под номером</label>
								<div class="col-sm-10">
									<?php foreach ($languages as $language) { ?>
									<div class="input-group">
										<span class="input-group-addon">
											<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
										</span>
										<input name="xds_coloring_theme_contact_hint[<?php echo $language['language_id']; ?>]" value="<?php echo $xds_coloring_theme_contact_hint[$language['language_id']]; ?>" class="form-control" />
									</div>
									<?php } ?>
								</div>
							</div>
							<hr>
							<div class="form-group">
                <label class="col-sm-2 control-label">Включить дополнительные контакты</label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <input type="radio" name="xds_coloring_theme_contact_add_toggle" value="1" <?php if ($xds_coloring_theme_contact_add_toggle) { echo 'checked'; } ?> /> Да
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="xds_coloring_theme_contact_add_toggle" value="0" <?php if (!$xds_coloring_theme_contact_add_toggle) { echo 'checked';} ?> /> Нет
                  </label>
                </div>
              </div>
							<hr>
							<div class="form-group">
								<label class="col-sm-2 control-label">Номера телефонов</label>
								<div class="col-sm-10">
									<table id="header-contacts-items" class="table table-bordered">
										<thead>
											<tr>
												<td class="nowrap"><i class="fa fa-image fa14"></i>&nbsp;&nbsp;Иконка</td>
												<td class="nowrap"><i class="fa fa-phone fa14"></i>&nbsp;&nbsp;Номер телефона</td>
												<td class="nowrap"><i class="fa fa-sort fa14"></i>&nbsp;&nbsp;Сортировка</td>
												<td></td>
											</tr>
										</thead>
										<tbody>
										<?php $cont_item_row = 0; ?>
										<?php foreach ($xds_coloring_theme_header_contacts as $xds_coloring_theme_header_contact) { ?>
											<tr id="item-row-cont<?php echo $cont_item_row; ?>">
												<td class="text-left">
													<a href="" id="thumb-image-cont<?php echo $cont_item_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $xds_coloring_theme_header_contact['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>"  /></a><input type="hidden" name="xds_coloring_theme_header_contact[<?php echo $cont_item_row; ?>][image]" value="<?php echo $xds_coloring_theme_header_contact['image']; ?>" id="input-image-cont<?php echo $cont_item_row; ?>" />
												</td>
												<td class="text-left">
													<?php foreach ($languages as $language) { ?>
													<div class="input-group pull-left">
														<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
														<input class="form-control" type="text" name="xds_coloring_theme_header_contact[<?php echo $cont_item_row; ?>][title][<?php echo $language['language_id']; ?>]" value="<?php echo $xds_coloring_theme_header_contact['title'][$language['language_id']]; ?>" />
													</div>
													<?php } ?>
												</td>
												<td class="text-left">
													<input  class="form-control" type="text" name="xds_coloring_theme_header_contact[<?php echo $cont_item_row; ?>][sort]" value="<?php echo $xds_coloring_theme_header_contact['sort']; ?>" />
												</td>
												<td class="text-right">
													<a class="btn btn-danger" onclick="$('#item-row-cont<?php echo $cont_item_row; ?>').remove();" data-toggle="tooltip" title="Удалить"><i class="fa fa-trash-o"></i></a>
												</td>
											</tr>
										<?php $cont_item_row++; ?>
										<?php } ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="3"></td>
												<td class="text-right"><a class="btn btn-primary" onclick="addItemCont();" data-toggle="tooltip" title="Добавить"><i class="fa fa-plus-circle"></i></a></td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
							<hr>
							<div class="form-group">
								<label class="col-sm-2 control-label">Почта для связи</label>
								<div class="col-sm-10">
									<?php foreach ($languages as $language) { ?>
									<div class="input-group">
										<span class="input-group-addon">
											<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
										</span>
										<input name="xds_coloring_theme_contact_email[<?php echo $language['language_id']; ?>]" value="<?php echo $xds_coloring_theme_contact_email[$language['language_id']]; ?>" class="form-control" />
									</div>
									<?php } ?>
								</div>
							</div>
							<hr>
							<div class="form-group">
								<label class="col-sm-2 control-label">График работы магазина</label>
								<div class="col-sm-10">
									<?php foreach ($languages as $language) { ?>
									<div class="input-group">
										<span class="input-group-addon">
											<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
										</span>
										<textarea name="xds_coloring_theme_schedule[<?php echo $language['language_id']; ?>]" rows="1" class="form-control"><?php echo $xds_coloring_theme_schedule[$language['language_id']]; ?></textarea>
									</div>
									<?php } ?>
								</div>
							</div>
							<hr>
							<div class="form-group">
								<label class="col-sm-2 control-label">Дополнительные контакты</label>
								<div class="col-sm-10">
									<table id="header-add-contacts-items" class="table table-bordered">
										<thead>
											<tr>
												<td class="nowrap"><i class="fa fa-image fa14"></i>&nbsp;&nbsp;Иконка</td>
												<td class="nowrap"><i class="fa fa-edit fa14"></i>&nbsp;&nbsp;Текст</td>
												<td class="nowrap"><i class="fa fa-link fa14"></i>&nbsp;&nbsp;Ссылка</td>
												<td class="nowrap"><i class="fa fa-sort fa14"></i>&nbsp;&nbsp;Сортировка</td>
												<td></td>
											</tr>
										</thead>
										<tbody>
										<?php $add_cont_item_row = 0; ?>
										<?php foreach ($xds_coloring_theme_additional_contacts as $xds_coloring_theme_additional_contact) { ?>
											<tr id="item-row-add-cont<?php echo $add_cont_item_row; ?>">
												<td class="text-left">
													<a href="" id="thumb-image-add-cont<?php echo $add_cont_item_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $xds_coloring_theme_additional_contact['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>"  /></a><input type="hidden" name="xds_coloring_theme_additional_contact[<?php echo $add_cont_item_row; ?>][image]" value="<?php echo $xds_coloring_theme_additional_contact['image']; ?>" id="input-image-add-cont<?php echo $add_cont_item_row; ?>" />
												</td>
												<td class="text-left">
													<?php foreach ($languages as $language) { ?>
													<div class="input-group pull-left">
														<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
														<input class="form-control" type="text" name="xds_coloring_theme_additional_contact[<?php echo $add_cont_item_row; ?>][title][<?php echo $language['language_id']; ?>]" value="<?php echo $xds_coloring_theme_additional_contact['title'][$language['language_id']]; ?>" />
													</div>
													<?php } ?>
												</td>
												<td class="text-left">
													<?php foreach ($languages as $language) { ?>
													<div class="input-group pull-left">
														<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
														<input class="form-control" type="text" name="xds_coloring_theme_additional_contact[<?php echo $add_cont_item_row; ?>][link][<?php echo $language['language_id']; ?>]" value="<?php echo $xds_coloring_theme_additional_contact['link'][$language['language_id']]; ?>" />
													</div>
													<?php } ?>
												</td>
												<td class="text-left">
													<input  class="form-control" type="text" name="xds_coloring_theme_additional_contact[<?php echo $add_cont_item_row; ?>][sort]" value="<?php echo $xds_coloring_theme_additional_contact['sort']; ?>" />
												</td>
												<td class="text-right">
													<a class="btn btn-danger" onclick="$('#item-row-add-cont<?php echo $add_cont_item_row; ?>').remove();" data-toggle="tooltip" title="Удалить"><i class="fa fa-trash-o"></i></a>
												</td>
											</tr>
										<?php $add_cont_item_row++; ?>
										<?php } ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="4"></td>
												<td class="text-right"><a class="btn btn-primary" onclick="addItemContAdd();" data-toggle="tooltip" title="Добавить"><i class="fa fa-plus-circle"></i></a></td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
						
						
						<div class="tab-pane" id="tab-home-carousel">
							<div class="form-group">
                <label class="col-sm-2 control-label">Включить</label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <input type="radio" name="xds_coloring_theme_home_carousel_toggle" value="1" <?php if ($xds_coloring_theme_home_carousel_toggle) { echo 'checked'; } ?> /> Да
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="xds_coloring_theme_home_carousel_toggle" value="0" <?php if (!$xds_coloring_theme_home_carousel_toggle) { echo 'checked';} ?> /> Нет
                  </label>
                </div>
              </div>
							<hr>
							<div class="form-group">
								<label class="col-sm-2 control-label">Баннер</label>
								<div class="col-sm-10">
									<select name="xds_coloring_theme_home_carousel_banner_id" class="form-control" >
										<?php foreach ($banners as $banner) { ?>
										<?php if ($banner['banner_id'] == $xds_coloring_theme_home_carousel_banner_id) { ?>
										<option value="<?php echo $banner['banner_id']; ?>" selected="selected"><?php echo $banner['name']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $banner['banner_id']; ?>"><?php echo $banner['name']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-footer-map">
							<div class="form-group">
                <label class="col-sm-2 control-label">Включить</label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <input type="radio" name="xds_coloring_theme_footer_map_toggle" value="1" <?php if ($xds_coloring_theme_footer_map_toggle) { echo 'checked'; } ?> /> Да
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="xds_coloring_theme_footer_map_toggle" value="0" <?php if (!$xds_coloring_theme_footer_map_toggle) { echo 'checked';} ?> /> Нет
                  </label>
                </div>
              </div>
							<hr>
							<div class="form-group">
								<label class="col-sm-2 control-label">Код карты</label>
								<div class="col-sm-10">
									<textarea name="xds_coloring_theme_footer_map" rows="10" class="form-control"><?php echo $xds_coloring_theme_footer_map; ?></textarea>
								</div>
							</div>
						</div>
						
						<div class="tab-pane" id="tab-pay-icons">
							<div class="form-group">
                <label class="col-sm-2 control-label">Включить</label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <input type="radio" name="xds_coloring_theme_pay_icons_toggle" value="1" <?php if ($xds_coloring_theme_pay_icons_toggle) { echo 'checked'; } ?> /> Да
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="xds_coloring_theme_pay_icons_toggle" value="0" <?php if (!$xds_coloring_theme_pay_icons_toggle) { echo 'checked';} ?> /> Нет
                  </label>
                </div>
              </div>
							<hr>
							<div class="form-group">
								<label class="col-sm-2 control-label">Баннер</label>
								<div class="col-sm-10">
									<select name="xds_coloring_theme_pay_icons_banner_id" class="form-control">
										<?php foreach ($banners as $banner) { ?>
										<?php if ($banner['banner_id'] == $xds_coloring_theme_pay_icons_banner_id) { ?>
										<option value="<?php echo $banner['banner_id']; ?>" selected="selected"><?php echo $banner['name']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $banner['banner_id']; ?>"><?php echo $banner['name']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						
						
					</div>
        </form>
      </div>
			<div class="panel-footer">
        2015 © <a href="http://xds.by/" target="_blank">Xds.by</a>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	function apply(){
		$(".alert").remove();
		$.post($("#form-xds-coloring-theme").attr('action'), $("#form-xds-coloring-theme").serialize(), function(html) {
			var $success = $(html).find(".alert-success, .alert-danger");
			if ($success.length > 0) {
				$(".alert-helper").before($success);
			}
		});
	}
</script>
<script type="text/javascript">
			$(window).scroll(function(){
					if ($(window).scrollTop() > 100){
							$("#control-buttons").addClass("stick");
					} else {
							$("#control-buttons").removeClass("stick");
					}
			});
</script>
<script type="text/javascript">
var item_row_help = <?php echo $item_row_help; ?>;

function addItemHelp() {
  html  = '<tr id="item-row-help' + item_row_help + '">';
  html += '<td class="text-left">';
	<?php foreach ($languages as $language) { ?>
	html += '<div class="input-group pull-left"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>';
	html += '<input class="form-control" type="text" name="xds_coloring_theme_help_menu_item[' + item_row_help + '][title][<?php echo $language['language_id']; ?>]" value="" />';
	html += '</div>';
	<?php } ?>
	html += '</td>';	
	html += '<td class="text-left">';
	<?php foreach ($languages as $language) { ?>
	html += '<div class="input-group pull-left"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>';
	html += '<input class="form-control"  type="text" name="xds_coloring_theme_help_menu_item[' + item_row_help + '][link][<?php echo $language['language_id']; ?>]" value="" />';
	html += '</div>';
	<?php } ?>
	html += '</td>';
	html += '<td class="text-left"><input class="form-control" type="text" name="xds_coloring_theme_help_menu_item[' + item_row_help + '][sort]" size="1" value="" /></td>';
	html += '<td class="text-right"><a class="btn btn-danger" onclick="$(\'#item-row-help' + item_row_help  + '\').remove();" data-toggle="tooltip" title="Удалить"><i class="fa fa-trash-o"></i></a></td>';
	html += '</tr>'; 
	
	$('#help-menu-items tbody').append(html);;
	
	item_row_help++;
}
</script>
<script type="text/javascript">
var item_row_main = <?php echo $item_row_main; ?>;

function addItemMain() {
  html  = '<tr id="item-row-main' + item_row_main + '">';
  html += '<td class="text-left">';
	<?php foreach ($languages as $language) { ?>
	html += '<div class="input-group pull-left"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>';
	html += '<input class="form-control" type="text" name="xds_coloring_theme_main_menu_item[' + item_row_main + '][title][<?php echo $language['language_id']; ?>]" value="" />';
	html += '</div>';
	<?php } ?>
	html += '</td>';
	html += '<td class="text-left">';
	<?php foreach ($languages as $language) { ?>
	html += '<div class="input-group pull-left"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>';
	html += '<input class="form-control"  type="text" name="xds_coloring_theme_main_menu_item[' + item_row_main + '][link][<?php echo $language['language_id']; ?>]" value="" />';
	html += '</div>';
	<?php } ?>
	html += '</td>';
	html += '<td class="text-left"><input class="form-control" type="text" name="xds_coloring_theme_main_menu_item[' + item_row_main + '][sort]" size="1" value="" /></td>';
	html += '<td class="text-right"><a class="btn btn-danger" onclick="$(\'#item-row-main' + item_row_main  + '\').remove();" data-toggle="tooltip" title="Удалить"><i class="fa fa-trash-o"></i></a></td>';
	html += '</tr>'; 
	
	$('#main-menu-items tbody').append(html);;
	
	item_row_main++;
}
</script>
<script type="text/javascript">
var cont_item_row = <?php echo $cont_item_row; ?>;

function addItemCont() {
  html  = '<tr id="item-row-cont' + cont_item_row + '">';
	html += '<td class="text-left"><a href="" id="thumb-image-cont' + cont_item_row + '" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>"  /></a><input type="hidden" name="xds_coloring_theme_header_contact[' + cont_item_row + '][image]" value="" id="input-image-cont' + cont_item_row + '" /></td>';
  html += '<td class="text-left">';
	<?php foreach ($languages as $language) { ?>
	html += '<div class="input-group pull-left"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>';
	html += '<input class="form-control" type="text" name="xds_coloring_theme_header_contact[' + cont_item_row + '][title][<?php echo $language['language_id']; ?>]" value="" />';
	html += '</div>';
	<?php } ?>
	html += '</td>';
	html += '<td class="text-left"><input class="form-control" type="text" name="xds_coloring_theme_header_contact[' + cont_item_row + '][sort]" size="1" value="" /></td>';
	html += '<td class="text-right"><a class="btn btn-danger" onclick="$(\'#item-row-cont' + cont_item_row  + '\').remove();" data-toggle="tooltip" title="Удалить"><i class="fa fa-trash-o"></i></a></td>';
	html += '</tr>'; 
	
	$('#header-contacts-items tbody').append(html);;
	
	cont_item_row++;
}
</script>
<script type="text/javascript">
var add_cont_item_row = <?php echo $add_cont_item_row; ?>;

function addItemContAdd() {
  html  = '<tr id="item-row-add-cont' + add_cont_item_row + '">';
	html += '<td class="text-left"><a href="" id="thumb-image-add-cont' + add_cont_item_row + '" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>"  /></a><input type="hidden" name="xds_coloring_theme_additional_contact[' + add_cont_item_row + '][image]" value="" id="input-image-add-cont' + add_cont_item_row + '" /></td>';
  html += '<td class="text-left">';
	<?php foreach ($languages as $language) { ?>
	html += '<div class="input-group pull-left"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>';
	html += '<input class="form-control" type="text" name="xds_coloring_theme_additional_contact[' + add_cont_item_row + '][title][<?php echo $language['language_id']; ?>]" value="" />';
	html += '</div>';
	<?php } ?>
	html += '</td>';
	html += '<td class="text-left">';
	<?php foreach ($languages as $language) { ?>
	html += '<div class="input-group pull-left"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>';
	html += '<input class="form-control" type="text" name="xds_coloring_theme_additional_contact[' + add_cont_item_row + '][link][<?php echo $language['language_id']; ?>]" value="" />';
	html += '</div>';
	<?php } ?>
	html += '</td>';
	html += '<td class="text-left"><input class="form-control" type="text" name="xds_coloring_theme_additional_contact[' + add_cont_item_row + '][sort]" size="1" value="" /></td>';
	html += '<td class="text-right"><a class="btn btn-danger" onclick="$(\'#item-row-add-cont' + add_cont_item_row  + '\').remove();" data-toggle="tooltip" title="Удалить"><i class="fa fa-trash-o"></i></a></td>';
	html += '</tr>'; 
	
	$('#header-add-contacts-items tbody').append(html);;
	
	add_cont_item_row++;
}
</script>
<script type="text/javascript">
var item_row_cat_links = <?php echo $item_row_cat_links; ?>;

function addItemCatLinks() {
  html  = '<tr id="item-row-cat-links' + item_row_cat_links + '">';
  html += '<td class="text-left">';
	<?php foreach ($languages as $language) { ?>
	html += '<div class="input-group pull-left"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>';
	html += '<input class="form-control" type="text" name="xds_coloring_theme_add_cat_links_item[' + item_row_cat_links + '][title][<?php echo $language['language_id']; ?>]" value="" />';
	html += '</div>';
	<?php } ?>
	html += '</td>';
	html += '<td class="text-left">';
	<?php foreach ($languages as $language) { ?>
	html += '<div class="input-group pull-left"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>';
	html += '<input class="form-control"  type="text" name="xds_coloring_theme_add_cat_links_item[' + item_row_cat_links + '][link][<?php echo $language['language_id']; ?>]" value="" />';
	html += '</div>';
	<?php } ?>
	html += '</td>';
	html += '<td class="text-left"><input class="form-control" type="text" name="xds_coloring_theme_add_cat_links_item[' + item_row_cat_links + '][sort]" size="1" value="" /></td>';
	html += '<td class="text-right"><a class="btn btn-danger" onclick="$(\'#item-row-cat-links' + item_row_cat_links  + '\').remove();" data-toggle="tooltip" title="Удалить"><i class="fa fa-trash-o"></i></a></td>';
	html += '</tr>'; 
	
	$('#category-links-items tbody').append(html);;
	
	item_row_cat_links++;
}
</script>
<?php echo $footer; ?>