<?php

require_once 'austincitylimits.civix.php';

function austincitylimits_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  if (strtolower($objectName) == 'address') {
    switch ($op) {
      case 'create':
      case 'edit':
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
