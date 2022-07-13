<?php declare(strict_types=1);

namespace PDFPlugin\Storefront\Controller;

use Shopware\Core\Checkout\Document\DocumentService;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\Framework\Validation\DataBag\QueryDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route(defaults={"_routeScope"={"storefront"}})
 */
class GeneratePDFController extends \Shopware\Storefront\Controller\StorefrontController
{


    // TODO: Import Product Media Repository in constructor

    // TODO: Generate PDF
    public function __construct(
        DocumentService $documentService
    )
    {
    }


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
//        TODO: HTML Template

        $productName = $data->get('productName');
        $productPrice = $data->get('productPrice');
        $productMediaCoverId = $data->get('productMediaCoverId');
        // TODO: Slice Variants

        return $this->renderStorefront('@PHPPlugin/storefront/page/product-detail/productDetailPDFTemplate.html.twig', [
          'name' => $productName,
          'price' => $productPrice
        ]);
    }
    // TODO: Handle Form from buy widget

    // TODO: Handle Form from shopping cart

}