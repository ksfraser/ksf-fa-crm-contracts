<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\CRM\Contracts\Service;
use Ksfraser\FrontAccounting\CRM\Contracts\DTO\ContractsDTO;
use Ksfraser\FrontAccounting\CRM\Contracts\Repository\ContractsRepository;
use ksfraser\RelatedTo\DTO\RelatedRecordDto;
final class ContractsService {
    private $repo;
    public function __construct(?ContractsRepository $repo = null) { $this->repo = $repo ?? new ContractsRepository(); }
    public function resolveById(int $id, ?RelatedRecordDto $dto = null): array {
        $row = $this->repo->findById($id);
        if ($row === null) {
            return array('dto' => new RelatedRecordDto('ksf_crm_contract', '0', 'ksf_crm_contract', '0', null), 'fields' => $this->fieldMap(), 'labels' => $this->labelMap(), 'url' => '#', 'label' => _('CRM Contract #') . (string) $id);
        }
        $r = ContractsDTO::fromArray($row);
        $dto = $dto ?? new RelatedRecordDto('ksf_crm_contract', (string) $r->contract_id, 'ksf_crm_contract', (string) $r->contract_id, null);
        return array(
            'dto' => $dto,
            'fields' => array(
                'contract_id' => (string) $r->contract_id,
                'title' => $r->title,
                'party_type' => $r->party_type,
                'party_id' => $r->party_name ?: (string) $r->party_id,
                'contract_type' => $r->contract_type,
                'start_date' => $r->start_date,
                'end_date' => $r->end_date,
                'renewal_date' => $r->renewal_date,
                'value' => $r->value,
                'status' => $r->status,
            ),
            'labels' => $this->labelMap(),
            'url' => '/modules/ksf_FA_CRM_Contracts/pages/crm_contract_form.php?action=edit&contract_id=' . $r->contract_id,
            'label' => $r->getDisplayLabel(),
        );
    }
    public function listAll(): array {
        $rows = $this->repo->findAll();
        $results = array();
        foreach ($rows as $row) {
            $r = ContractsDTO::fromArray($row);
            $results[] = array(
                'id' => $r->contract_id,
                'dto' => new RelatedRecordDto('ksf_crm_contract', (string) $r->contract_id, 'ksf_crm_contract', (string) $r->contract_id, null),
                'fields' => array(
                    'contract_id' => (string) $r->contract_id,
                    'title' => $r->title,
                    'party_type' => $r->party_type,
                    'party_id' => $r->party_name ?: (string) $r->party_id,
                    'contract_type' => $r->contract_type,
                    'start_date' => $r->start_date,
                    'status' => $r->status,
                ),
                'labels' => $this->labelMap(),
                'url' => '/modules/ksf_FA_CRM_Contracts/pages/crm_contract_form.php?action=edit&contract_id=' . $r->contract_id,
                'label' => $r->getDisplayLabel(),
            );
        }
        return $results;
    }
    public function insertContract(array $data): int {
        return $this->repo->insertContract($data);
    }
    public function updateState(int $contractId, string $status, string $renewalDate = null, string $value = null): void {
        $this->repo->updateState($contractId, $status, $renewalDate, $value);
        crm_contract_state_changed((string) $contractId, '', $status, array());
    }
    public function fieldMap(): array { return $this->labelMap(); }
    public function labelMap(): array {
        return array(
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
    }
    public function getStateTransitions(string $currentState): array {
        $transitions = array(
            'draft' => array('active' => _('Activate')),
            'active' => array('expired' => _('Expire'), 'terminated' => _('Terminate')),
        );
        return isset($transitions[$currentState]) ? $transitions[$currentState] : array();
    }
    public function isAllowedTransition(string $fromState, string $toState): bool {
        $transitions = $this->getStateTransitions($fromState);
        return isset($transitions[$toState]);
    }
}
