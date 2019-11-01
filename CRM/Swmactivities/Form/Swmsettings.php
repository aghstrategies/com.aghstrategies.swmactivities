<?php

use CRM_Swmactivities_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Swmactivities_Form_Swmsettings extends CRM_Core_Form {

  public function getSwmsettings() {
    try {
      $setting = civicrm_api3('Setting', 'getsingle', array(
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
    return $setting;
  }

  public function buildQuickForm() {
    // add form elements
    $this->addEntityRef('swmactivities_templates', ts('Message Templates to create activities for when sent'), array(
      'entity' => 'OptionValue',
      'api' => [
        'label_field' => "label",
        'search_field' => "label",
        'id_field' => "name",
        'params' => [
          'option_group_id' => ['IN' => ["msg_tpl_workflow_case", "msg_tpl_workflow_contribution", "msg_tpl_workflow_event", "msg_tpl_workflow_friend", "msg_tpl_workflow_membership", "msg_tpl_workflow_meta", "msg_tpl_workflow_petition", "msg_tpl_workflow_pledge", "msg_tpl_workflow_volunteer", "msg_tpl_workflow_uf"]],
        ],
      ],
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

    $default = $this->getSwmsettings();
    // print_r($default); die();
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
    if (!empty($values['swmactivities_templates'])) {
      $setting = explode(',', $values['swmactivities_templates']);
    }
    foreach ($setting as $key => $messageTemplateId) {
      try {
        $options = civicrm_api3('MessageTemplate', 'get', [
          'sequential' => 1,
          'id' => $messageTemplateId,
          'api.OptionValue.get' => ['id' => "\$value.workflow_id"],
        ]);
      }
      catch (CiviCRM_API3_Exception $e) {
        $error = $e->getMessage();
        CRM_Core_Error::debug_log_message(ts('API Error %1', array(
          'domain' => 'com.aghstrategies.swmactivities',
          1 => $error,
        )));
      }
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
    CRM_Core_Session::setStatus(E::ts('Settings have been updated'), SUCCESS, SUCCESS);
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
