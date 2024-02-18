<?php

namespace Tests\Unit;

use App\Repositories\InvoicesRepository as RepositoriesInvoicesRepository;
use PHPUnit\Framework\TestCase;

use App\DTO\InvoiceDto;
use App\DTO\ItemDto;

class InvoiceTest extends TestCase
{   
    public function test_that_total_can_be_determined(){        
        
        $invoice = new InvoiceDto(
            [
                new ItemDto(1, "apple", 3),
                new ItemDto(2, "pear", 3),
            ]
        );    

        $invoiceRepository = new RepositoriesInvoicesRepository();
        
        $total = $invoiceRepository->total($invoice);

        $this->assertEquals(6,$total, "Totals are not the same.");
    }
}
