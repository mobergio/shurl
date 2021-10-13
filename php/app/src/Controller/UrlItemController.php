<?php

namespace App\Controller;

use App\Entity\UrlItem;
use App\Form\UrlItemType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\Translator;

/**
 * @Route("")
 */
class UrlItemController extends AbstractController
{

    public const MIN_SLUG_LENGTH = 8;

    private const VALID_CHARACTERS = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_-";

    private const LOCALE = "en";

    /**
     * @Route("/", name="url_item_new", methods={"GET","POST"})
     */
    public function new(Request $request, SessionInterface $session): Response
    {
        $urlItem = new UrlItem();
        $urlItem->setSlug($this->createRandomSlug());
        $form = $this->createForm(UrlItemType::class, $urlItem);
        $form->handleRequest($request);

        $translator    = new Translator(self::LOCALE);
        $description[] = $translator->trans('description.copy_paste_url');
        $description[] = sprintf($translator->trans('description.min_length'), self::MIN_SLUG_LENGTH);

        if ($form->isSubmitted() && $form->isValid()) {
            $urlItem->setSlug(str_replace(' ', '-', $urlItem->getSlug()));

            if (strlen(trim($urlItem->getSlug())) == 0 || is_null($urlItem->getSlug())) {
                $urlItem->setSlug($this->createRandomSlug());
            } elseif (strlen(trim($urlItem->getSlug())) < self::MIN_SLUG_LENGTH) {
                $errorMessage = sprintf($translator->trans('error.min_length'), self::MIN_SLUG_LENGTH);

                return $this->doRender($urlItem, $form, $errorMessage, $description);
            } elseif (!$this->isSlugValid($urlItem->getSlug())) {
                $errorMessage = $translator->trans('error.slug_invalid');

                return $this->doRender($urlItem, $form, $errorMessage, $description);
            }

            $result = $this->getDoctrine()->getRepository(UrlItem::class)->findOneBy(["url" => $urlItem->getUrl()]);
            if ($result) {
                $session->set('message', 'message.reused_slug');

                return $this->redirectToRoute(
                    'url_item_show',
                    ['slug' => $result->getSlug()],
                    Response::HTTP_SEE_OTHER
                );
            }

            $errorMessage = null;
            $hasFailed    = false;
            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($urlItem);
                $entityManager->flush();
            } catch (UniqueConstraintViolationException $e) {
                $hasFailed    = true;
                $errorMessage = $translator->trans('error.slug_already_in_use');
            } catch (Exception $e) {
                $hasFailed    = true;
                $errorMessage = $translator->trans('error.unknown_error');
            }
            if ($hasFailed) {
                return $this->doRender($urlItem, $form, $errorMessage, $description);
            }

            return $this->redirectToRoute(
                'url_item_show',
                ['slug' => $urlItem->getSlug()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->doRender($urlItem, $form, null, $description);
    }

    /**
     * @Route("/{slug}", name="url_item_show", methods={"GET"})
     */
    public function show(UrlItem $urlItem, SessionInterface $session): Response
    {
        $message = $session->get('message', null);
        $session->set('message', null);

        return $this->render('url_item/show.html.twig', [
            'url_item' => $urlItem,
            'message'  => $message,
        ]);
    }

    /**
     * @return string
     */
    public function createRandomSlug(): string
    {
        $str_result = self::VALID_CHARACTERS;

        return substr(str_shuffle($str_result), 0, self::MIN_SLUG_LENGTH);
    }

    /**
     * @param \App\Entity\UrlItem                   $urlItem
     * @param \Symfony\Component\Form\FormInterface $form
     * @param string|null                           $errorMessage
     * @param array                                 $description
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function doRender(
        UrlItem $urlItem,
        FormInterface $form,
        ?string $errorMessage,
        array $description
    ): Response {
        return $this->renderForm('url_item/new.html.twig', [
            'url_item'    => $urlItem,
            'form'        => $form,
            'error'       => $errorMessage,
            'description' => $description,
        ]);
    }

    /**
     * @param $slug
     *
     * @return bool
     */
    public function isSlugValid($slug): bool
    {
        preg_match('/[^' . str_replace('-', '\-', self::VALID_CHARACTERS) . '$]/', $slug, $matches);

        return sizeof($matches) === 0;
    }
}
