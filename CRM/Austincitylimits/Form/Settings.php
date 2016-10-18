<?php
/**
 * @file
 * Admin form.
 *
 * Copyright (C) 2016, AGH Strategies, LLC <info@aghstrategies.com>
 * Licensed under the GNU Affero Public License 3.0 (see LICENSE.txt)
 */
require_once 'CRM/Core/Form.php';

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
    $params = array(
      'sequential' => 1,
      'custom_59' => array('IS NULL' => 1),
      'geo_code_1' => array('IS NOT NULL' => 1),
      'geo_code_2' => array('IS NOT NULL' => 1),
      'state_province_id' => 1042,
      'location_type_id' => 1,
    );
    // THIS WILL ONLY GET Contacts whose PRIMARY ADDRESS is home

    // TODO fix options bassed on API
    // if ($options) {
    //   $params['options'] = $options;
    // }
    try {
      $addresses = civicrm_api3('Contact', 'get', $params);
    }
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      CRM_Core_Error::debug_log_message(ts('API Error %1', array(
        'domain' => 'com.aghstrategies.austincitylimits',
        1 => $error,
      )));
    }
    foreach ($addresses['values'] as $address) {
      $geo = new CRM_Austincitylimits_Geo($address['geo_code_1'], $address['geo_code_2']);
      $geo->saveDistrict($address['contact_id']);
    }
  }

  /**
   * Save values.
   */
  public function postProcess() {
    // $values = $this->exportValues();
    austincitylimits_allAddresses();
    parent::postProcess();
  }

}
