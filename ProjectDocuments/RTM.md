# ksf_FA_CRM_Contracts — Requirements Traceability Matrix

| Req ID | Requirement | Source | Implemented In | Verified By |
|--------|-------------|--------|----------------|-------------|
| CRM-06 | Contracts w/ renewal tracking | HR-CRM_gaps.md C6 | ContractsService | unit tests |
| FR-CC-01 | Create contract | CRM-06 | ContractsRepository::insertContract | ContractsDtoTest |
| FR-CC-02 | List contracts | CRM-06 | ContractsService::listAll | (tab controller) |
| FR-CC-03 | Resolve contract by id | CRM-06 | ContractsService::resolveById | (capability hook) |
| FR-CC-04 | Update contract | CRM-06 | ContractsService::updateState | (state transitions) |
| FR-CC-05 | RELATED_TO_FIELDS | CRM-06 | hooks.php RELATED_TO_FIELDS | (capability) |
| NF-CC-01 | Native db_* calls | AGENTS_ARCH | repository | code review |
| NF-CC-02 | PHP 7.3 compatible | AGENTS_ARCH | all src | code review |
| NF-CC-03 | Unit tests | AGENTS_ARCH | tests/Unit | phpunit |
