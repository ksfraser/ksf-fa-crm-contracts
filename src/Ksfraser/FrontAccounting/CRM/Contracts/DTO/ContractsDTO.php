<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\CRM\Contracts\DTO;
final class ContractsDTO {
    public $contract_id;
    public $title;
    public $party_type;
    public $party_id;
    public $party_name;
    public $contract_type;
    public $start_date;
    public $end_date;
    public $renewal_date;
    public $value;
    public $status;
    public $created_at;
    public $updated_at;
    public function __construct(
        int $contract_id,
        string $title = '',
        string $party_type = '',
        int $party_id = 0,
        string $party_name = '',
        string $contract_type = '',
        string $start_date = '',
        string $end_date = '',
        string $renewal_date = '',
        string $value = '0.00',
        string $status = 'active',
        string $created_at = '',
        string $updated_at = ''
    ) {
        $this->contract_id = $contract_id;
        $this->title = $title;
        $this->party_type = $party_type;
        $this->party_id = $party_id;
        $this->party_name = $party_name;
        $this->contract_type = $contract_type;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->renewal_date = $renewal_date;
        $this->value = $value;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
    public function getDisplayLabel(): string {
        if ($this->title !== '') {
            return sprintf(_('%s — %s'), $this->title, $this->party_name ?: $this->party_type);
        }
        return _('CRM Contract #') . (string) $this->contract_id;
    }
    public function toArray(): array {
        return array(
            'contract_id' => $this->contract_id,
            'title' => $this->title,
            'party_type' => $this->party_type,
            'party_id' => $this->party_id,
            'party_name' => $this->party_name,
            'contract_type' => $this->contract_type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'renewal_date' => $this->renewal_date,
            'value' => $this->value,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        );
    }
    public static function fromArray(array $data): self {
        return new self(
            (int) ($data['contract_id'] ?? 0),
            (string) ($data['title'] ?? ''),
            (string) ($data['party_type'] ?? ''),
            (int) ($data['party_id'] ?? 0),
            (string) ($data['party_name'] ?? ''),
            (string) ($data['contract_type'] ?? ''),
            (string) ($data['start_date'] ?? ''),
            (string) ($data['end_date'] ?? ''),
            (string) ($data['renewal_date'] ?? ''),
            (string) ($data['value'] ?? '0.00'),
            (string) ($data['status'] ?? 'active'),
            (string) ($data['created_at'] ?? ''),
            (string) ($data['updated_at'] ?? '')
        );
    }
}
