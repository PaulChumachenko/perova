<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
    <input type="hidden" name="isearch_status" value="1" />
    <div class="page-header">
      <div class="container-fluid">
        <div class="pull-right">
          <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary save-changes"><i class="fa fa-save"></i></button>
          <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
        <h1><?php echo $heading_title; ?></h1>
        <ul class="breadcrumb">
          <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
    <div class="container-fluid">
      <?php echo (empty($data['iSearch']['LicensedOn'])) ? base64_decode('ICAgIDxkaXYgY2xhc3M9ImFsZXJ0IGFsZXJ0LWRhbmdlciBmYWRlIGluIj4NCiAgICAgICAgPGJ1dHRvbiB0eXBlPSJidXR0b24iIGNsYXNzPSJjbG9zZSIgZGF0YS1kaXNtaXNzPSJhbGVydCIgYXJpYS1oaWRkZW49InRydWUiPsOXPC9idXR0b24+DQogICAgICAgIDxoND5XYXJuaW5nISBVbmxpY2Vuc2VkIHZlcnNpb24gb2YgdGhlIG1vZHVsZSE8L2g0Pg0KICAgICAgICA8cD5Zb3UgYXJlIHJ1bm5pbmcgYW4gdW5saWNlbnNlZCB2ZXJzaW9uIG9mIHRoaXMgbW9kdWxlISBZb3UgbmVlZCB0byBlbnRlciB5b3VyIGxpY2Vuc2UgY29kZSB0byBlbnN1cmUgcHJvcGVyIGZ1bmN0aW9uaW5nLCBhY2Nlc3MgdG8gc3VwcG9ydCBhbmQgdXBkYXRlcy48L3A+PGRpdiBzdHlsZT0iaGVpZ2h0OjVweDsiPjwvZGl2Pg0KICAgICAgICA8YSBjbGFzcz0iYnRuIGJ0bi1kYW5nZXIiIGhyZWY9ImphdmFzY3JpcHQ6dm9pZCgwKSIgb25jbGljaz0iJCgnYVtocmVmPSNzdXBwb3J0XScpLnRyaWdnZXIoJ2NsaWNrJykiPkVudGVyIHlvdXIgbGljZW5zZSBjb2RlPC9hPg0KICAgIDwvZGl2Pg==') : '' ?>
      <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
      <?php } ?>
      <?php if ($success_message) { ?>
      <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success_message; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
      <?php } ?>

      <div class="panel panel-default">
        <div class="panel-body">
          <ul class="nav nav-tabs">
            <?php foreach ($tabs as $tab) { ?>
            <li><a class="isearch_tab" href="#<?php echo $tab['id']; ?>" data-toggle="tab"><?php echo $tab['name']; ?></a></li>
            <?php } ?>
          </ul>
          
          <div class="tab-content">
          <?php foreach ($tabs as $tab) { ?>
            <div class="tab-pane" id="<?php echo $tab['id']; ?>">
              <?php require_once $tab['file']; ?>
            </div>
          <?php } ?>
          </div>
          
        </div>
      </div>
    </div>
  </form>
</div>
<script type="text/javascript">

  var jq = jQuery; // Intended for MijoShop

  var selected_tab = function() {
    var tab_id = localStorage.getItem('isearch_tab');

    if (!tab_id) {
      tab_id = jq('.isearch_tab').first().attr('href');
      localStorage.setItem('isearch_tab', tab_id);
    }

    return tab_id;
  }

  jq('.isearch_tab').click(function() {
    localStorage.setItem('isearch_tab', jq(this).attr('href'));
  });

  jq('.isearch_tab[href="' + selected_tab() + '"]').trigger('click');

</script>
<?php echo $footer; ?>