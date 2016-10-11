<?php
/**
 * @file
 * Admin form.
 *
 * Copyright (C) 2016, AGH Strategies, LLC <info@aghstrategies.com>
 * Licensed under the GNU Affero Public License 3.0 (see LICENSE.txt)
 */
require_once 'CRM/Core/Form.php';
require_once '../../../austincitylimits.php';

/**
 * Administrative settings for the extension.
 */
class CRM_Austincitylimits_Form_Settings extends CRM_Core_Form {
  /**
   * Build the form.
   */
  public function buildQuickForm() {
    $this->addSelect('customfielddistrict', array(
      'entity' => 'CustomField',
      'field' => 'custom_field',
      'multiple' => FALSE,
      'label' => ts('Custom Fields', array('domain' => 'com.aghstrategies.austincitylimits')),
      'placeholder' => ts('- any -', array('domain' => 'com.aghstrategies.austincitylimits')),
    ));
    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => ts('Save', array('domain' => 'com.aghstrategies.austincitylimits')),
        'isDefault' => TRUE,
      ),
    ));
    // Send element names to the form.
    $this->assign('elementNames', 'customfielddistrict');
    parent::buildQuickForm();
  }
  /**
   * Save values.
   */
  public function postProcess() {
    $values = $this->exportValues();
    try {
      $result = civicrm_api3('Setting', 'create', array('austincitylimits_customfielddistrict' => $values['customfielddistrict']));
      CRM_Core_Session::setStatus(ts('picked a custom field to save the city council district to', array('domain' => 'com.aghstrategies.austincitylimits')), 'Settings saved', 'success');
    }
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      CRM_Core_Error::debug_log_message(t('API Error: %1', array(1 => $error, 'domain' => 'com.aghstrategies.austincitylimits')));
      CRM_Core_Session::setStatus(ts('Error saving custom field for city council district', array('domain' => 'com.aghstrategies.austincitylimits')), 'Error', 'error');
    }
    austincitylimits_civicrm_allAddresses();
    parent::postProcess();
  }
}
