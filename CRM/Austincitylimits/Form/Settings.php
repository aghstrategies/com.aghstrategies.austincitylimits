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
   * Save values.
   */
  public function postProcess() {
    // $values = $this->exportValues();
    austincitylimits_civicrm_allAddresses();
    parent::postProcess();
  }

}
