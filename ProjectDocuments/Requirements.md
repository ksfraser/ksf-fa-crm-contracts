# ksf_FA_CRM_Contracts — Requirements

## Purpose
Manage CRM contract records, parties, terms, renewal tracking and status.

## Business Rules (CRM-06)
- A contract has a title, party (type + id), contract type, dates, value and status.
- Status transitions: draft → active → expired | terminated.

## Functional Requirements
- FR-CC-01: Create a CRM contract.
- FR-CC-02: List all CRM contracts (summary table).
- FR-CC-03: Resolve a single contract by id (related-record linking).
- FR-CC-04: Update contract status/renewal date/value.
- FR-CC-05: Expose CRM contract fields via RELATED_TO_FIELDS.

## Non-Functional Requirements
- NF-CC-01: All database access uses native FA db_* calls.
- NF-CC-02: PHP 7.3 compatible runtime.
- NF-CC-03: Unit tests cover DTO round-trip and label resolution.
