<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\CRM\Contracts\Tests\Unit;
use PHPUnit\Framework\TestCase;
use Ksfraser\FrontAccounting\CRM\Contracts\DTO\ContractsDTO;
class ContractsDtoTest extends TestCase
{
    public function testDtoRoundTripsThroughArray(): void {
        $dto = new ContractsDTO(5, 'Service Agreement', 'customer', 100, 'Acme Pty Ltd', 'service', '2026-01-01', '2026-12-31', '2026-11-01', '15000.00', 'active');
        $arr = $dto->toArray();
        $this->assertSame(5, $arr['contract_id']);
        $this->assertSame('Service Agreement', $arr['title']);
        $this->assertSame('customer', $arr['party_type']);
        $this->assertSame(100, $arr['party_id']);
        $this->assertSame('Acme Pty Ltd', $arr['party_name']);
        $this->assertSame('15000.00', $arr['value']);
        $this->assertSame('active', $arr['status']);
        $dto2 = ContractsDTO::fromArray($arr);
        $this->assertSame($dto->contract_id, $dto2->contract_id);
        $this->assertSame($dto->title, $dto2->title);
        $this->assertSame($dto->status, $dto2->status);
    }
    public function testDisplayLabelUsesTitleAndPartyWhenSet(): void {
        $dto = new ContractsDTO(1, 'Service Agreement', 'customer', 100, 'Acme Pty Ltd');
        $this->assertStringContainsString('Service Agreement', $dto->getDisplayLabel());
        $this->assertStringContainsString('Acme Pty Ltd', $dto->getDisplayLabel());
    }
    public function testDisplayLabelFallsBackToId(): void {
        $dto = new ContractsDTO(9, '', '', 0, '', '', '', '', '', '0.00', 'active');
        $this->assertStringContainsString('CRM Contract #9', $dto->getDisplayLabel());
    }
}
