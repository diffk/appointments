<?php

namespace Example\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class DefaultController
 * @package Example\AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     *
     * Главная страница
     *
     * @ApiDoc(
     *  section="Default",
     *  statusCodes={
     *      200="ok",
     *      400="internal Error",
     *  }
     * )
     *
     * @Get("/")
     */
    public function indexAction()
    {
        return $this->render('ExampleAppBundle::base.html.twig');
    }

    /**
     * Форма для обновления записи о расписании
     *
     * @param Request $request
     *
     * @Post("/form")
     * @Get("/form")
     *
     * @return JsonResponse
     */
    public function getForm(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $obj = new \stdClass();
        $obj->day = null;
        $obj->start = null;
        $obj->id = null;

        $form = $this->createFormBuilder($obj)
            ->add('day', TextType::class)
            ->add('start', EmailType::class)
            ->add('id', TextType::class)
            ->getForm();


        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);


            if ($form->isValid()) {

                // transform form data and use api
                $id = $request->get($form->getName())['id'];
                $day = $request->get($form->getName())['day'];
                $start = $request->get($form->getName())['start'];

                $request->request->set('day', $day);
                $request->request->set('start', $start);

                $pathParam = ['id' => $id];

                return $this->forward(
                    'ExampleAppBundle:Api:updateShedule',
                    $pathParam
                );

            } else {
                return new JsonResponse(["message" => 'invalid data'], 400);
            }

        } else {

            return new Response($this->render(
                'ExampleAppBundle::form.html.twig',
                array(
                    'form' => $form->createView(),
                )
            )->getContent());
        }

    }
}
