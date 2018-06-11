<tr>
  <td class="simplecheckout-table-form-left">
    <?php if ($required) { ?>
      <span class="simplecheckout-required">*</span>
    <?php } ?>
    <?php echo $label ?>
  </td>
  <td class="simplecheckout-table-form-right">
    <?php if ($type == 'select') { ?>
      <select name="<?php echo $name ?>" id="<?php echo $id ?>" <?php echo $reload ? 'data-onchange="reloadAll"' : ''?>>
        <?php foreach ($values as $info) { ?>
          <option value="<?php echo $info['id'] ?>" <?php echo $value == $info['id'] ? 'selected="selected"' : '' ?>><?php echo $info['text'] ?></option>
        <?php } ?>
      </select>
    <?php } elseif ($type == 'radio') { ?>
      <?php foreach ($values as $info) { ?>
        <label><input type="radio" name="<?php echo $name ?>" id="<?php echo $id ?>" value="<?php echo $info['id'] ?>" <?php echo $value == $info['id'] ? 'checked="checked"' : '' ?> <?php echo $reload ? 'data-onchange="reloadAll"' : ''?>><?php echo $info['text'] ?></label>
      <?php } ?>
    <?php } elseif ($type == 'checkbox') { ?>
      <?php foreach ($values as $info) { ?>
        <input type="hidden" name="<?php echo $name ?>[<?php echo $info['id'] ?>]" value="0">
        <label><input type="checkbox" name="<?php echo $name ?>[<?php echo $info['id'] ?>]" id="<?php echo $id ?>" value="1" <?php echo !empty($value[$info['id']]) ? 'checked="checked"' : '' ?> <?php echo $reload ? 'data-onchange="reloadAll"' : ''?>><?php echo $info['text'] ?></label>
      <?php } ?>
    <?php } elseif ($type == 'textarea') { ?>
      <textarea name="<?php echo $name ?>" id="<?php echo $id ?>" placeholder="<?php echo $placeholder ?>" <?php echo $reload ? 'data-onchange="reloadAll"' : ''?>><?php echo $value ?></textarea>
    <?php } elseif ($type == 'captcha') { ?>
      <input type="text" name="<?php echo $name ?>" id="<?php echo $id ?>" value="" placeholder="<?php echo $placeholder ?>" <?php echo $reload ? 'data-onchange="reloadAll"' : ''?>>
      <div class="simple-captcha-container"><img src="index.php?<?php echo $additional_path ?>route=common/simple_connector/captcha&t=<?php echo time() ?>" alt="" id="captcha" /></div>
    <?php } elseif ($type == 'file') { ?>
      <input type="button" value="<?php echo $button_upload; ?>" data-file="<?php echo $id ?>" class="button">
      <div id="text_<?php echo $id ?>" style="margin-top:3px;max-width:200px;"><?php echo $filename ?></div>
      <input type="hidden" name="<?php echo $name ?>" id="<?php echo $id ?>" value="<?php echo $value ?>">
    <?php } else { ?>
      <input type="<?php echo $type == 'date' ? 'text' : $type ?>" name="<?php echo $name ?>" id="<?php echo $id ?>" value="<?php echo $value ?>" placeholder="<?php echo $placeholder ?>" <?php echo $attrs ?> <?php echo $reload ? 'data-onchange="reloadAll"' : ''?>>
    <?php } ?> 
    <?php if (!empty($rules)) { ?>
      <div class="simplecheckout-rule-group" data-for="<?php echo $id ?>">
        <?php foreach ($rules as $rule) { ?>
          <div <?php echo $rule['display'] && !$rule['passed'] ? '' : 'style="display:none;"' ?> data-for="<?php echo $id ?>" data-rule="<?php echo $rule['id'] ?>" class="simplecheckout-error-text simplecheckout-rule" <?php echo $rule['attrs'] ?>><?php echo $rule['text'] ?></div>
        <?php } ?>
      </div>
    <?php } ?>
    <?php if ($description) { ?>
      <div class="simplecheckout-tooltip" data-for="<?php echo $id ?>"><?php echo $description ?></div>
    <?php } ?>
  </td>
</tr>