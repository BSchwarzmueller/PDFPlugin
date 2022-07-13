<?php declare(strict_types=1);

namespace PDFPlugin\Storefront\Controller;

use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\Framework\Validation\DataBag\QueryDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Slim\Http\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route(defaults={"_routeScope"={"storefront"}})
 */
class GeneratePDFController extends \Shopware\Storefront\Controller\StorefrontController
{

    // TODO: Import Product Media Repository in constructor

    // TODO: Generate PDF


    /**
     * @RouteScope (scopes={"storefront"})
     * @Route("/generatePDFProductDetail", name="frontend.generate.pdf.product.detail", methods={"GET"})
     *
     * @param Request $request
     * @param QueryDataBag $data
     * @param SalesChannelContext $context
     * @return Response
     */
    public function handleProductDetailForm(Request $request, QueryDataBag $data, SalesChannelContext $context): Response
    {
        dd($data);
    }
    // TODO: Handle Form from buy widget

    // TODO: Handle Form from shopping cart

}