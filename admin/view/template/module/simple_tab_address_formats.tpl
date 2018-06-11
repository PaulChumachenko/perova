<div ng-controller="simpleAddressFormatsController" ng-init="settings.addressFormats = !empty(settings.addressFormats) ? settings.addressFormats : {};addressFormats = settings.addressFormats">
  <table class="form">
    <tbody>
      <tr>
        <td>
        </td>
        <td ng-repeat="l in languages"><img ng-src="view/image/flags/{{l.image}}" />&nbsp;{{l.name}}</td>
      </tr>
      <tr ng-repeat="group in groups">
        <td ng-init="addressFormats[group.customer_group_id] = !empty(addressFormats[group.customer_group_id]) ? addressFormats[group.customer_group_id] : {}">
          <div style="margin-bottom:5px;font-weight:bold;">{{group.name}}</div>
          <?php echo $entry_address_format ?>
          <div class="help" ng-repeat="field in settings.fields" ng-if="field.custom">{{field.label[currentLanguage]}} = <span>&#123;</span>{{field.id}}<span>&#125;</span></div>
        </td>
        <td ng-repeat="l in languages"><textarea cols="25" rows="15" placeholder="<?php echo $l->get('text_help_address_formats', true) ?>" ng-model="addressFormats[group.customer_group_id][l.code]"></textarea></td>
      </tr>
    </tbody>
  </table>
</div>