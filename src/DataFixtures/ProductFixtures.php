<?php

namespace App\DataFixtures;

use App\Entity\Invoices;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        foreach ($this->productsData($manager) as [$name, $JIRA, $JiraTask, $ClientJiraTask, $Description, $Price, $Unit, $Amount, $Invoice]) {
            $product = new Product();
            $product->setName($name);
            $product->setJIRA($JIRA);
            $product->setJiraTask($JiraTask);
            $product->setClientJiraTask($ClientJiraTask);
            $product->setDescription($Description);
            $product->setPrice($Price);
            $product->setUnit($Unit);
            $product->setAmount($Amount);
            $product->setInvoice($Invoice);
            $manager->persist($product);
        }
        $manager->flush();

    }

    private function productsData($manager)
    {
        $first = $manager->getRepository(Invoices::class)->find(1);
        $second = $manager->getRepository(Invoices::class)->find(2);
        $third = $manager->getRepository(Invoices::class)->find(3);
        $forth = $manager->getRepository(Invoices::class)->find(4);
        return [['Izstrāde' . random_int(1, 25),
            1,
            'JiraTask',
            'None',
            'Website Prototype',
            5555.40,
            1,
            1,
            $first
        ],
            ['Izstrāde' . random_int(1, 25),
                1,
                'JiraTask',
                'None',
                'Website Prototype',
                1000.40,
                1,
                1,
                $first
            ],
            ['Izstrāde' . random_int(1, 25),
                1,
                'JiraTask',
                'None',
                'Website Prototype',
                2222.40,
                1,
                1,
                $second
            ],
            ['Izstrāde' . random_int(1, 25),
                1,
                'JiraTask',
                'None',
                'Website Prototype',
                5555.40,
                1,
                1,
                $second
            ],
            ['Izstrāde' . random_int(1, 25),
                1,
                'JiraTask',
                'None',
                'Website Prototype',
                3333.40,
                1,
                1,
                $third
            ],
            ['Izstrāde' . random_int(1, 25),
                1,
                'None',
                'None',
                'Website Prototype 1',
                10000.40,
                1,
                 2,
                $forth
            ],
            ['Izstrāde' . random_int(1, 25),
                1,
                'None',
                'None',
                'Website Prototype 2',
                10000.40,
                1,
                1,
                $forth
            ],
            ['Izstrāde' . random_int(1, 25),
                1,
                'None',
                'None',
                'Website Prototype 3',
                10000.40,
                1,
                1,
                $forth
            ]];
    }

}
