<?php

declare(strict_types=1);

namespace Ksfraser\FrontAccounting\CRM\Contracts\App;

use ksfraser\FrontAccounting\Common\App\AbstractAppShell;
use ksfraser\FrontAccounting\Common\Menu\FAModuleMenu;
use Ksfraser\FrontAccounting\CRM\Contracts\Controller\ContractsTabController;

class ContractsAppShell extends AbstractAppShell
{
    public function __construct()
    {
        parent::__construct('crmcontracts', 'index.php', 'view', 'crm_contracts');

        $this->add_module(_("CRM Contracts"));

        $menu = new FAModuleMenu(
            'modules/ksf_FA_CRM_Contracts/index.php',
            'view',
            ''
        );

        $menu->addItem('crm_contracts', _("CRM Contracts"), MENU_INQUIRY)
             ->registerWithApp($this, 'SA_CRM_CONTRACTS_VIEW');

        $this->add_extensions();
    }
}
