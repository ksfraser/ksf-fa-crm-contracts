<?php

declare(strict_types=1);

namespace Ksfraser\FrontAccounting\CRM\Contracts\App;

use ksfraser\FrontAccounting\Common\App\AbstractAppShell;
use ksfraser\FrontAccounting\Common\App\TabRegistration;

class ContractsAppShell extends AbstractAppShell
{
    public function __construct()
    {
        parent::__construct('crmcontracts', 'index.php', 'view', 'crm_contracts');

        $this->registerTab(new TabRegistration(
            'crm_contracts',
            'CRM Contracts',
            'SA_CRM_CONTRACTS_VIEW',
            null,
            10,
            0,
            null,
            null,
            array()
        ));
    }
}
