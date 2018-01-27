<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sale;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class SaleController extends Controller
{
    /**
     * @Route("/sales/", name="listAllSales")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listSalesData()
    {
        $repo = $this->getDoctrine()->getRepository(Sale::class);
        $sales = $repo->getAllSalesData();

        return $this->render('sales/listSales.html.twig', ['sales' => $sales]);
    }

    /**
     * @Route("/sales/discount", name="listAllSalesWithDiscount")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listDiscountedSales()
    {
        $repo = $this->getDoctrine()->getRepository(Sale::class);
        $sales = $repo->getAllDiscountedSalesData();

        return $this->render('sales/listSales.html.twig', ['sales' => $sales]);
    }

    /**
     * @Route("/sales/discount/{percent}", name="listAllSalesWithPercentDiscount")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listSalesDiscountWithGivenPercent($percent)
    {
        $repo = $this->getDoctrine()->getRepository(Sale::class);
        $sales = $repo->getDiscountedSalesDataWithPercent($percent);

        return $this->render('sales/listSales.html.twig', ['sales' => $sales]);
    }

    /**
     * @Route("/sales/{id}", name="singleSaleDetails")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showDetailsForSingleSale($id)
    {
        $repo = $this->getDoctrine()->getRepository(Sale::class);
        $sale = $repo->detailsForSingleSale($id)[0];

        return $this->render('sales/saleDetails.html.twig', ['sale' => $sale]);
    }


    /**
     * @param $id
     * @Route("/clients/{id}", name="clientSalesData")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showDataAboutClientSales($id)
    {
        $repo = $this->getDoctrine()->getRepository(Sale::class);
        $salesData = $repo->getSaleDataAboutClient($id);
        $summary = $repo->getSummarizedDataAboutClientSales($id);

        return $this->render('sales/clientSales.html.twig',
            ['salesData' => $salesData,
                'summary' => $summary[0]]);
    }
}
