<?php

namespace App\DataFixtures;

use App\Entity\Invoices;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InvoicesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->invoicesData() as [$client,$type,$issuer,$owner,$issueDate,$dueDate,$amount,$paid,$unpaid]){
        $invoice = new Invoices();
        $invoice->setClient($client);
        $invoice->setType($type);
        $invoice->setIssuer($issuer);
        $invoice->setOwner($owner);
        $invoice->setIssueDate($issueDate);
        $invoice->setDueDate($dueDate);
        $invoice->setAmount($amount);
        $invoice->setPaid($paid);
        $invoice->setUnpaid($unpaid);
        $manager->persist($invoice);
        }
        $manager->flush();
    }
    private function invoicesData(){

        return [[
            'Random Client '.random_int(1,25),
            'Invoice',
            'Efumo SSC',
            'Efumo SSC',
            new \DateTime('2022-07-22'),
            new \DateTime('2022-07-30'),
            0,
            0,
            0
        ],
        [
            'Random Client '.random_int(1,25),
            'Invoice',
            'Efumo SSC',
            'Efumo SSC',
            new \DateTime('2022-06-22'),
            new \DateTime('2022-07-22'),
            0,
            0,
            0
        ],
            [
                'Random Client '.random_int(1,25),
                'Invoice',
                'Efumo SSC',
                'Efumo SSC',
                new \DateTime('2022-05-22'),
                new \DateTime('2022-06-12'),
                0,
                0,
                0
            ],
            [
                'Random Client '.random_int(1,25),
                'Invoice',
                'Business Instruments',
                'Business Instruments',
                new \DateTime('2022-06-25'),
                new \DateTime('2022-07-11'),
                0,
                0,
                0
            ]];
    }
}
