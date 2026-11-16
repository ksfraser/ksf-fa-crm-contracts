-- ksf_FA_CRM_Contracts: CRM contract tables

CREATE TABLE IF NOT EXISTS `0_ksf_crm_contracts` (
  `contract_id`   INT(11) NOT NULL AUTO_INCREMENT,
  `title`         VARCHAR(255) NOT NULL,
  `party_type`    VARCHAR(32) NOT NULL COMMENT 'e.g. customer, supplier, employee',
  `party_id`      INT(11) NOT NULL,
  `contract_type` VARCHAR(64) NOT NULL COMMENT 'e.g. service, lease, NDA',
  `start_date`    DATE NOT NULL,
  `end_date`      DATE DEFAULT NULL,
  `renewal_date`  DATE DEFAULT NULL,
  `value`         DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `status`        VARCHAR(24) NOT NULL DEFAULT 'active' COMMENT 'active|expired|terminated|draft',
  `created_at`    DATETIME NOT NULL,
  `updated_at`    DATETIME NOT NULL,
  PRIMARY KEY (`contract_id`),
  KEY `idx_party` (`party_type`, `party_id`),
  KEY `idx_type` (`contract_type`),
  KEY `idx_status` (`status`),
  KEY `idx_renewal` (`renewal_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
