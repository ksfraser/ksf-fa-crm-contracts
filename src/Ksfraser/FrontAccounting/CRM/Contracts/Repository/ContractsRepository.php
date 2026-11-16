<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\CRM\Contracts\Repository;
final class ContractsRepository {
    private string $table = 'ksf_crm_contracts';
    public function findAll(): array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return array(); }
        $sql = "SELECT * FROM " . TB_PREF . $this->table . " ORDER BY created_at DESC, contract_id DESC";
        $result = db_query($sql, 'Could not load CRM contracts');
        if ($result === false) { return array(); }
        $rows = array();
        while (($row = db_fetch_assoc($result)) !== false && $row !== null) { $rows[] = $row; }
        return $rows;
    }
    public function findById(int $id): ?array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return null; }
        $sql = "SELECT * FROM " . TB_PREF . $this->table . " WHERE contract_id = " . (int) $id;
        $result = db_query($sql, 'Could not load CRM contract');
        if ($result === false) { return null; }
        $row = db_fetch_assoc($result);
        return $row === false ? null : $row;
    }
    public function insertContract(array $data): int {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return 0; }
        $sql = "INSERT INTO " . TB_PREF . $this->table . " (
                title, party_type, party_id, contract_type, start_date, end_date, renewal_date, value, status, created_at, updated_at)
            VALUES ("
            . $this->escape($data['title'] ?? '') . ", "
            . $this->escape($data['party_type'] ?? '') . ", "
            . $this->intVal($data['party_id'] ?? 0) . ", "
            . $this->escape($data['contract_type'] ?? '') . ", "
            . $this->escape($data['start_date'] ?? '') . ", "
            . $this->escape($data['end_date'] ?? null) . ", "
            . $this->escape($data['renewal_date'] ?? null) . ", "
            . $this->floatVal($data['value'] ?? 0) . ", "
            . $this->escape($data['status'] ?? 'active') . ", "
            . $this->escape(date('Y-m-d H:i:s')) . ", "
            . $this->escape(date('Y-m-d H:i:s')) . ")";
        $this->dbQuery($sql);
        return $this->dbInsertId();
    }
    public function updateState(int $contractId, string $status, string $renewalDate = null, string $value = null): void {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return; }
        $sets = ["status = " . $this->escape($status)];
        $sets[] = "renewal_date = " . $this->escape($renewalDate);
        $sets[] = "value = " . $this->escape($value ?? '');
        $sets[] = "updated_at = " . $this->escape(date('Y-m-d H:i:s'));
        $sql = "UPDATE " . TB_PREF . $this->table . " SET " . implode(', ', $sets)
            . " WHERE contract_id = " . $this->intVal($contractId);
        $this->dbQuery($sql);
    }
    private function dbQuery($sql, $errorMsg = null) {
        return db_query($sql, $errorMsg);
    }
    private function dbInsertId() {
        return db_insert_id();
    }
    private function escape($value): string {
        if ($value === null) { return 'NULL'; }
        return function_exists('db_escape') ? db_escape($value) : addslashes((string) $value);
    }
    private function intVal($value): int {
        return $value !== null ? (int) $value : 0;
    }
    private function floatVal($value): float {
        return (float) $value;
    }
}
