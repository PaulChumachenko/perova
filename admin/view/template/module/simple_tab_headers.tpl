<div ng-controller="simpleRowController" ng-init="settings.headers = !empty(settings.headers) ? settings.headers : [];rows=settings.headers">
    <vtabs extendable="true" removable-method="deleteRow(id)" extendable-placeholder="<?php echo $l->get('entry_new_header_id', true) ?>" extendable-method="createRow(id)">
        <vtab title="{{header.id}}" removable="true" removable-id="{{header.id}}" tooltip="{{!empty(header.label[currentLanguage]) ? header.label[currentLanguage] : header.id }}" ng-repeat="header in rows">
            <table class="form">
                <tr ng-init="header.label = !empty(header.label) ? header.label : {}">
                    <td>
                        <?php echo $l->get('entry_header_label'); ?>
                    </td>
                    <td>
                        <div ng-repeat="l in languages"><img ng-src="view/image/flags/{{l.image}}" />&nbsp;<input type="text" size="50" ng-model="header.label[l.code]"></div>
                    </td>    
                </tr>
            </table>
        </vtab>
    </vtabs>
</div>