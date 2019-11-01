<?php

use CRM_Swmactivities_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Swmactivities_Form_Swmsettings extends CRM_Core_Form {
  public function buildQuickForm() {

    // add form elements
    $this->addEntityRef('swmactivities_templates', ts('Message Templates to create activities for when sent'), array(
      'entity' => 'MessageTemplate',
      'api' => ['label_field' => "msg_title", 'search_field' => "msg_title"],
      'multiple' => TRUE,
      'select' => array('minimumInputLength' => 0),
    ));

    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => E::ts('Submit'),
        'isDefault' => TRUE,
      ),
    ));

    try {
      $default = civicrm_api3('Setting', 'getsingle', array(
        'return' => 'swmactivities_templates',
      ));
    }
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      CRM_Core_Error::debug_log_message(ts('API Error %1', array(
        'domain' => 'com.aghstrategies.swmactivities',
        1 => $error,
      )));
    }
    if (!empty($default['swmactivities_templates'])) {
      $this->setDefaults($default);
    }

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  public function postProcess() {
    $values = $this->exportValues();
    $setting = [];
    if (!empty($values['swmsetting'])) {
      $setting = explode(',', $values['swmsetting']);
    }

    try {
      $result = civicrm_api3('Setting', 'create', array(
        'swmactivities_templates' => $setting,
      ));
    }
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      CRM_Core_Error::debug_log_message(ts('API Error %1', array(
        'domain' => 'com.aghstrategies.swmactivities',
        1 => $error,
      )));
    }
    CRM_Core_Session::setStatus(E::ts('Settings have been updated'));
    parent::postProcess();
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

}
