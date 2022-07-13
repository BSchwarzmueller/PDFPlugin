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

    // TODO: Generate PDF with Document Service
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

        $productName = $data->get('productName');
        $productPrice = $data->get('productPrice');
        $productMediaCoverUrl = $data->get('productMediaCoverUrl');

        $variants = [];

        foreach ($data as $key => $val) {
            if ($key == 'productName' || $key == 'productPrice' || $key == 'productMediaCoverUrl') {
                continue;
            }
            $variants[] = "{$key}: {$val}";
        }

        // TODO: Replace with Document Service ?
        return $this->renderStorefront('@PHPPlugin/storefront/page/product-detail/productDetailPDFTemplate.html.twig', [
            'coverUrl' => $productMediaCoverUrl,
            'name' => $productName,
            'price' => $productPrice,
            'variants' => $variants
        ]);
    }

    /**
     * @RouteScope (scopes={"storefront"})
     * @Route("/generatePDFShoppingCart", name="frontend.generate.pdf.shopping.cart", methods={"GET"})
     *
     * @param Request $request
     * @param QueryDataBag $data
     * @param SalesChannelContext $context
     * @return Response
     */
    public function handleShoppingCartForm(Request $request, QueryDataBag $data, SalesChannelContext $context): Response
    {
        $lineItems = [];
        $itemId = 0;

        foreach ($data as $key => $val) {
            $slicedArray = explode('-', $key);
            $key = $slicedArray[0];
            $currentItemId = $slicedArray[1];

            if($itemId != $currentItemId) {
                $itemId = $currentItemId;
                $lineItems[$itemId] = [];
            }
            $lineItem = [$key => $val];
            $lineItems[$itemId][] = $lineItem;
        }

        return $this->renderStorefront('@PHPPlugin/storefront/page/product-detail/shoppingCartPDFTemplate.html.twig', [
            'lineItems' => $lineItems
        ]);
    }
}