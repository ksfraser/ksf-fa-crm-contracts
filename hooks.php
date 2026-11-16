<?php
declare(strict_types=1);
require_once __DIR__ . '/ComposerDependencies.php';
\Ksfraser\FrontAccounting\CRM\Contracts\Utils\ComposerDependencies::ensure(__DIR__);
$autoload_path = dirname(__FILE__) . '/vendor/autoload.php';
if (file_exists($autoload_path)) { require_once $autoload_path; }
require_once __DIR__ . '/includes/common.inc';
require_once __DIR__ . '/includes/events.inc';
use ksfraser\RelatedTo\DTO\RelatedRecordDto;
define('SS_ksf_FA_CRM_Contracts', 198 << 8);
if (!defined('SA_CRM_CONTRACTS_VIEW')) {
    define('SA_CRM_CONTRACTS_VIEW', SS_ksf_FA_CRM_Contracts | 1);
    define('SA_CRM_CONTRACTS_MANAGE', SS_ksf_FA_CRM_Contracts | 2);
    define('SA_CRM_CONTRACTS_ADMIN', SS_ksf_FA_CRM_Contracts | 3);
}
class hooks_ksf_FA_CRM_Contracts extends hooks {
    const RESPONDERS = array('RELATED_TO_FIELDS', 'CRM_CONTRACTS_RESOLVE', 'CRM_CONTRACTS_FIELDS', 'CRM_CONTRACTS_LIST');
    var $module_name = 'ksf_FA_CRM_Contracts';
    var $version = '1.0.0';
    function install_tabs($app) { }
    function install_options($app) { }
    function install_access() {
        $security_sections[SS_ksf_FA_CRM_Contracts] = _("CRM Contracts");
        $security_areas['SA_CRM_CONTRACTS_VIEW'] = array(SA_CRM_CONTRACTS_VIEW, _("View CRM contracts"));
        $security_areas['SA_CRM_CONTRACTS_MANAGE'] = array(SA_CRM_CONTRACTS_MANAGE, _("Manage CRM contracts"));
        $security_areas['SA_CRM_CONTRACTS_ADMIN'] = array(SA_CRM_CONTRACTS_ADMIN, _("Administer CRM contracts"));
        return array($security_areas, $security_sections);
    }
    function activate_extension($company, $check_only = true) {
        if ($check_only) { return true; }
        \Ksfraser\FrontAccounting\CRM\Contracts\Utils\ComposerDependencies::ensure(__DIR__);
        $this->update_databases($company, array(
            '0_ksf_crm_contracts.sql' => array('ksf_crm_contracts'),
        ), false);
        return true;
    }
    private function ensure_composer_dependencies() {
        \Ksfraser\FrontAccounting\CRM\Contracts\Utils\ComposerDependencies::ensure(__DIR__);
    }
    public function getModuleConstants(&$data, $opts = null) {
        $data['constants'] = array(
            'SS_ksf_FA_CRM_Contracts' => SS_ksf_FA_CRM_Contracts,
            'SA_CRM_CONTRACTS_VIEW' => SA_CRM_CONTRACTS_VIEW,
            'SA_CRM_CONTRACTS_MANAGE' => SA_CRM_CONTRACTS_MANAGE,
            'SA_CRM_CONTRACTS_ADMIN' => SA_CRM_CONTRACTS_ADMIN,
        );
        return true;
    }
    public function getModuleCapabilities(&$data, $opts = null) {
        $data['capabilities'] = self::RESPONDERS;
        return true;
    }
    public function hasCapability(&$data, $opts) {
        $capability = is_array($opts) && isset($opts['capability']) ? $opts['capability'] : null;
        if ($capability === null && isset($data['capability'])) { $capability = $data['capability']; }
        $data['has_capability'] = in_array($capability, self::RESPONDERS, true);
        return $data['has_capability'];
    }
    public function respondToCapabilityRequest(&$data, $opts = null) {
        $request = is_array($opts) && isset($opts['request']) ? $opts['request'] : (isset($data['request']) ? $data['request'] : 'capabilities');
        $data['request'] = $request;
        $data['module'] = $this->module_name;
        if (strpos($request, 'has:') === 0) { return $this->hasCapability($data, array('capability' => substr($request, 4))); }
        switch ($request) {
            case 'capabilities': return $this->getModuleCapabilities($data, $opts);
            case 'constants': return $this->getModuleConstants($data, $opts);
            default: $data['error'] = 'Unknown request type: ' . $request; return null;
        }
    }
    public function RELATED_TO_FIELDS(&$data, $opts = null) {
        if (!isset($data['module']) || !is_string($data['module'])) { $data['error'] = 'RELATED_TO_FIELDS requires data[module]'; return null; }
        if (!isset($data['type']) || !is_string($data['type'])) { $data['error'] = 'RELATED_TO_FIELDS requires data[type]'; return null; }
        if ($data['type'] !== 'ksf_crm_contract') { return null; }
        if (!isset($data['fields']) || !is_array($data['fields'])) { $data['fields'] = array(); }
        $data['fields'] = array_merge($data['fields'], array(
            'contract_id' => _('Contract ID'),
            'title' => _('Title'),
            'party_type' => _('Party Type'),
            'party_id' => _('Party'),
            'contract_type' => _('Contract Type'),
            'start_date' => _('Start Date'),
            'end_date' => _('End Date'),
            'renewal_date' => _('Renewal Date'),
            'value' => _('Value'),
            'status' => _('Status'),
        ));
        return null;
    }
    public function CRM_CONTRACTS_RESOLVE(&$data, $opts = null) {
        if (!isset($data['contract_id']) || !is_numeric($data['contract_id'])) { $data['error'] = 'CRM_CONTRACTS_RESOLVE requires data[contract_id]'; return null; }
        if (!isset($data['dto']) || !$data['dto'] instanceof RelatedRecordDto) { $data['error'] = 'CRM_CONTRACTS_RESOLVE requires data[dto] as RelatedRecordDto'; return null; }
        $service = new \Ksfraser\FrontAccounting\CRM\Contracts\Service\ContractsService();
        $data['result'] = $service->resolveById((int) $data['contract_id'], $data['dto']);
        return null;
    }
    public function CRM_CONTRACTS_FIELDS(&$data, $opts = null) {
        $data['fields'] = array(
            'contract_id' => _('Contract ID'),
            'title' => _('Title'),
            'party_type' => _('Party Type'),
            'party_id' => _('Party'),
            'contract_type' => _('Contract Type'),
            'start_date' => _('Start Date'),
            'end_date' => _('End Date'),
            'renewal_date' => _('Renewal Date'),
            'value' => _('Value'),
            'status' => _('Status'),
        );
        return null;
    }
    public function CRM_CONTRACTS_LIST(&$data, $opts = null) {
        $service = new \Ksfraser\FrontAccounting\CRM\Contracts\Service\ContractsService();
        $data['result'] = $service->listAll();
        return null;
    }
}
