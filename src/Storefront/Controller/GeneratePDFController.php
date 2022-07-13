<?php declare(strict_types=1);

namespace PDFPlugin\Storefront\Controller;

use Shopware\Core\Checkout\Document\DocumentService;
use Shopware\Core\Framework\Adapter\Twig\TwigEnvironment;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\Framework\Validation\DataBag\QueryDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Twig\Loader\FilesystemLoader;


/**
 * @Route(defaults={"_routeScope"={"storefront"}})
 */
class GeneratePDFController extends \Shopware\Storefront\Controller\StorefrontController
{
    /**
     * @RouteScope (scopes={"storefront"})
     * @Route("/generatePDFProductDetail", name="frontend.generate.pdf.product.detail", methods={"GET"})
     *
     * @param Request $request
     * @param QueryDataBag $data
     * @param SalesChannelContext $context
     * @return Response
     */
    public function handleProductDetailForm(Request $request, QueryDataBag $data, SalesChannelContext $context): void
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

        $this->showPDF('detail', $productName, $productMediaCoverUrl, $productPrice, $variants, null);

    }

    private function showPDF( $type, $productName, $productMediaCoverUrl, $productPrice, $productVariants, $lineItems): void
    {
        $loader = new FilesystemLoader(__DIR__ . '/Templates');
        $twig = new TwigEnvironment($loader);

        if($type=='detail')
        {
            $html = $twig->render('@PHPPlugin/storefront/page/product-detail/productDetailPDFTemplate.html.twig', [
                'coverUrl' => $productMediaCoverUrl,
                'name' => $productName,
                'price' => $productPrice,
                'variants' => $productVariants
            ]);
        }

        if($type=='cart')
        {
            $html = $twig->render('@PHPPlugin/storefront/page/product-detail/shoppingCartPDFTemplate.html.twig', [
                'lineItems' => $lineItems
            ]);
        }

        $dompdf = new DOMPDF();

        $dompdf->loadHtml($html);
        $dompdf->render();

        $dompdf->stream('document.pdf');
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

            if ($itemId != $currentItemId) {
                $itemId = $currentItemId;
                $lineItems[$itemId] = [];
            }
            $lineItem = [$key => $val];
            $lineItems[$itemId][] = $lineItem;
        }

        $this->showPDF('cart', null, null, null, null, $lineItems);
    }
}