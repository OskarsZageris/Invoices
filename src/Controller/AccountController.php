<?php

namespace App\Controller;

use App\Entity\Invoices;


use App\Entity\Notes;
use App\Entity\Product;
use App\Form\InvoicesType;
use App\Form\NotesType;
use App\Form\NoteType;
use App\Form\ProductsType;
use App\Form\VATType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $invoices = $entityManager->getRepository(Invoices::class)->findBy([], ['id' => 'DESC']);
        $products = $entityManager->getRepository(Product::class)->findAll();
        $this->setPriceForInvoices($doctrine, $invoices, $products);
        //sets unpaid amount depending on how much is paid
        foreach ($invoices as $invoice) {
            $invoice->setUnpaid($invoice->getAmount() - $invoice->getPaid());
            $entityManager->persist($invoice);
        }
        $entityManager->flush();
        return $this->render('account/index.html.twig',
            ['invoices' => $invoices]);
    }

    private function setPriceForInvoices($doctrine, $invoices, $products)
    {
        $entityManager = $doctrine->getManager();
        foreach ($invoices as $invoice) {
            $invoice->setAmount(0);
            $entityManager->persist($invoice);
        }
        $entityManager->flush();
        foreach ($products as $product) {
            $invoice = $product->getInvoice();
            $product->setTotalSum($product->getPrice() * $product->getUnit() * $product->getAmount());
            $sum = $product->getTotalSum();
            $invoice->setAmount($invoice->getAmount() + $sum);
            $entityManager->persist($invoice);
        }
        $entityManager->flush();
    }

    /**
     * @Route("/delete-invoice/{id}", name="delete_invoice")
     */
    public function deleteInvoice(Invoices $invoice, Request $request, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($invoice);
        $entityManager->flush();
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/edit-invoice/{id}", methods={"GET","POST"}, name="edit_invoice")
     */
    public function editInvoice(Invoices $invoice, Request $request, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $invoices = $entityManager->getRepository(Invoices::class)->findAll();
        $products = $entityManager->getRepository(Product::class)->findAll();
        $this->setPriceForInvoices($doctrine, $invoices, $products);
        $id = $invoice->getId();
        $invoice = $entityManager->getRepository(Invoices::class)->find($invoice->getId());
        $is_invalid = null;
        $form = $this->createForm(InvoicesType::class, $invoice);
        $noteForm = $this->createForm(NotesType::class);

        //refactor with db to find all related products
        $products = $entityManager->getRepository(Product::class)->findAll();
        foreach ($products as $product) {
            if ($product->getInvoice()->getId() === $id) {
                $relatedProducts[] = $product;
            }
        }
        $productsForm = $this->createForm(ProductsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $issueDateMonth = $request->request->all('invoices')['IssueDate']['month'];
            $issueDateDay = $request->request->all('invoices')['IssueDate']['day'];
            $issueDateYear = $request->request->all('invoices')['IssueDate']['year'];
            $dueDateMonth = $request->request->all('invoices')['DueDate']['month'];
            $dueDateDay = $request->request->all('invoices')['DueDate']['day'];
            $dueDateYear = $request->request->all('invoices')['DueDate']['year'];
            $invoice->setClient($request->request->all('invoices')['Client']);
            $invoice->setType($request->request->all('invoices')['Type']);
            $invoice->setIssuer($request->request->all('invoices')['Issuer']);
            $invoice->setOwner($request->request->all('invoices')['Owner']);
            $invoice->setIssueDate(new \DateTime("$issueDateDay-$issueDateMonth-$issueDateYear"));
            $invoice->setDueDate(new \DateTime("$dueDateDay-$dueDateMonth-$dueDateYear"));
            $invoice->setPaid($request->request->all('invoices')['Paid']);
            $entityManager->persist($invoice);
            $entityManager->flush();
        }

        $note = $entityManager->getRepository(Notes::class)->findBy(['invoice' => $invoice]);
        if (!empty($note)) {
            $note = $note[0];
        } else {
            $note = new Notes();
        }


        $noteForm->handleRequest($request);
        if ($noteForm->isSubmitted() && $noteForm->isValid()) {
            $note->setInvoice($invoice);
            $note->setDescription($request->request->all('notes')['description']);
//dd($request);
            $note->setPercent($request->request->all('notes')['percent']);

            $entityManager->persist($note);
            $entityManager->flush();
        }

        $productsForm->handleRequest($request);
        if ($productsForm->isSubmitted() && $productsForm->isValid()) {


            $product = new Product();
            $product->setInvoice($invoice);
            $product->setName($request->request->all('products')['name']);
            $product->setJIRA($request->request->all('products')['JIRA']);
            $product->setJiraTask($request->request->all('products')['JiraTask']);
            $product->setClientJiraTask($request->request->all('products')['ClientJiraTask']);
            $product->setDescription($request->request->all('products')['Description']);
            $product->setPrice($request->request->all('products')['Price']);
            $product->setUnit($request->request->all('products')['Unit']);
            $product->setAmount($request->request->all('products')['Amount']);
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirect($request->headers->get('referer'));
        }

        if (empty($relatedProducts)) {
            $relatedProducts = [];
        }
        $forms = $this->manyForms($relatedProducts, $request, $doctrine);
        $this->setPriceForInvoices($doctrine, $invoices, $products);
        $percent = $invoice->getAmount() * $note->getPercent() / 100;
        return $this->render('account/edit_invoice.html.twig', [
            'invoice' => $invoice,
            'is_invalid' => $is_invalid,
            'form' => $form->createView(),
            'products' => $relatedProducts,
            'productsForm' => $productsForm->createView(),
            'forms' => $forms,
            'noteForm' => $noteForm->createView(),
            'note' => $note,
            'percent' => $percent
        ]);
    }

    private function manyForms($products, $request, $doctrine)
    {
        $forms = [];
        foreach ($products as $index => $product) {
            $forms[$index] = $this->container
                ->get('form.factory')
                ->createNamed('form' . $index, ProductsType::class);
        }
        foreach ($forms as $oneFrom) {
            $oneFrom->handleRequest($request);
            if ($oneFrom->isSubmitted() && $oneFrom->isValid()) {
                $id = $request->request->all()['id'];
                $formNumber = $request->request->all()['form'];
                $entityManager = $doctrine->getManager();
                $product = $entityManager->getRepository(Product::class)->find($id);
                $product->setName($request->request->all("$formNumber")['name']);
                $product->setJIRA($request->request->all("$formNumber")['JIRA']);
                $product->setJiraTask($request->request->all("$formNumber")['JiraTask']);
                $product->setClientJiraTask($request->request->all("$formNumber")['ClientJiraTask']);
                $product->setDescription($request->request->all("$formNumber")['Description']);
                $product->setPrice($request->request->all("$formNumber")['Price']);
                $product->setUnit($request->request->all("$formNumber")['Unit']);
                $product->setAmount($request->request->all("$formNumber")['Amount']);
                $entityManager->persist($product);
                $entityManager->flush();
            }
        }

        foreach ($forms as &$form) {
            $form = $form->createView();
        }
        return $forms;
    }

    /**
     * @Route("/delete-product/{id}", name="delete_product")
     */
    public function deleteProduct(Product $product, Request $request, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($product);
        $entityManager->flush();
        return $this->redirect($request->headers->get('referer'));
    }

}
