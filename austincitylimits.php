<?php

require_once 'austincitylimits.civix.php';

function austincitylimits_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  if (strtolower($objectName) == 'address') {
    switch ($op) {
      case 'create':
      case 'edit':
        // TODO: make sure location type is "home"
        // check for lat and long and if its texas if so load districts
        if ($objectRef->state_province_id == 1042 &&
        !empty($objectRef->geo_code_1) &&
        !empty($objectRef->geo_code_2) &&
        !empty($objectRef->contact_id) &&
        strtolower($objectRef->geo_code_1) != 'null' &&
        strtolower($objectRef->geo_code_2) != 'null' &&
        strtolower($objectRef->contact_id) != 'null') {
          //load geoPHP
          $geo = new CRM_Austincitylimits_Geo($objectRef->geo_code_1, $objectRef->geo_code_2);
          $geo->saveDistrict($objectRef->contact_id);
          // if address is edited to no longer fufill if statement paramaters
          // then no different from deleting
          break;
        }

      case 'delete':
        deleteDistrict($objectRef->contact_id);
        break;
    }

  }
}

/**
 * Implements hook_civicrm_validateForm().
 */
function austincitylimits_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
  if (($formName != "CRM_Contact_Form_Contact" && $formName != 'CRM_Contact_Form_Inline_CustomData')
    || empty($form->_contactId)) {
    return;
  }

  foreach ($fields as $fieldName => $val) {
    if (strpos($fieldName, 'custom_7_') === 0 || $fieldName == 'custom_7') {
      try {
        $address = civicrm_api3('Address', 'getsingle', array(
          'return' => "state_province_id,geo_code_1,geo_code_2",
          'contact_id' => $form->_contactId,
          'location_type_id' => "Home",
        ));
      }
      catch (CiviCRM_API3_Exception $e) {
        $error = $e->getMessage();
        CRM_Core_Error::debug_log_message(ts('API Error', array(
          'domain' => 'com.aghstrategies.austincitylimits',
          1 => $error,
        )));
        return;
      }   
      if (CRM_Utils_Array::value('state_province_id', $address) != 1042
        || empty($address["geo_code_1"])
        || empty($address["geo_code_2"])) {
        return;
      }
      // We have enough address info to calculate the district, therefore we
      // shouldn't let it be saved manually.
      $data = &$form->controller->container();
      if ($formName == 'CRM_Contact_Form_Inline_CustomData') {
        unset($data['values']['CustomData'][$fieldName]);
      }
      else {
        unset($data['values']['Contact'][$fieldName]);
      }
    }
  }
}
/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function austincitylimits_civicrm_config(&$config) {
  _austincitylimits_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function austincitylimits_civicrm_xmlMenu(&$files) {
  _austincitylimits_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function austincitylimits_civicrm_install() {
  _austincitylimits_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function austincitylimits_civicrm_uninstall() {
  _austincitylimits_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function austincitylimits_civicrm_enable() {
  _austincitylimits_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function austincitylimits_civicrm_disable() {
  _austincitylimits_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function austincitylimits_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _austincitylimits_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function austincitylimits_civicrm_managed(&$entities) {
  _austincitylimits_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function austincitylimits_civicrm_caseTypes(&$caseTypes) {
  _austincitylimits_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function austincitylimits_civicrm_angularModules(&$angularModules) {
  _austincitylimits_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function austincitylimits_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _austincitylimits_civix_civicrm_alterSettingsFolders($metaDataFolders);
}
