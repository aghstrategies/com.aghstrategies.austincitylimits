<?php
/**
 * @file
 * Admin form.
 *
 * Copyright (C) 2016, AGH Strategies, LLC <info@aghstrategies.com>
 * Licensed under the GNU Affero Public License 3.0 (see LICENSE.txt)
 */
require_once 'CRM/Core/Form.php';
require_once 'austincitylimits.php';
/**
 * Administrative settings for the extension.
 */
class CRM_Austincitylimits_Form_Settings extends CRM_Core_Form {
  /**
   * Build the form.
   */
  public function buildQuickForm() {
    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => ts('Run Austin City Limits for all Addresses', array('domain' => 'com.aghstrategies.austincitylimits')),
        'isDefault' => TRUE,
      ),
    ));
    // Send element names to the form.
    // $this->assign('elementNames', array('customfielddistrict'));
    parent::buildQuickForm();
  }

  /**
   * [austincitylimits_allAddresses description]
   */
  public function austincitylimits_allAddresses() {
    try {
      $addresses = civicrm_api3('Address', 'get', array(
        'sequential' => 1,
        'location_type_id' => "Home",
        'state_province_id' => 1042,
        'options' => array('limit' => ""),
      ));
    }
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      CRM_Core_Error::debug_log_message(ts('API Error %1', array(
        'domain' => 'com.aghstrategies.austincitylimits',
        1 => $error,
      )));
    }
    foreach ($addresses['values'] as $address) {
      $objectId = $address['id'];
      $objectRef->state_province_id = $address['state_province_id'];
      $objectRef->geo_code_1 = $address['geo_code_1'];
      $objectRef->geo_code_2 = $address['geo_code_2'];
      $objectRef->location_type_id = $address['location_type_id'];
      $objectRef->contact_id = $address['contact_id'];
      austincitylimits_civicrm_post('edit', 'address', $objectId, $objectRef);
    }
  }

  /**
   * Save values.
   */
  public function postProcess() {
    // $values = $this->exportValues();
    $this->austincitylimits_allAddresses();
    parent::postProcess();
  }

}
