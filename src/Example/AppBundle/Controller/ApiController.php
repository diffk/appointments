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

use Example\AppBundle\Entity\Doctor;


/**
 * Class ApiController
 * @package Example\AppBundle\Controller
 */
class ApiController extends Controller
{
    /**
     *
     * Список врачей
     *
     * @ApiDoc(
     *  section="Api",
     *  statusCodes={
     *      200="ok",
     *      400="internal Error",
     *  }
     * )
     *
     * @Get("/doctor")
     */
    public function getDoctorList()
    {
        $doctors = $this->getDoctrine()->getManager('example')
            ->getRepository('ExampleAppBundle:Doctor')
            ->findAll();

        $doctorList = [];

        if ($doctors) {
            foreach ($doctors as $doctor) {

                $doctorList[] = $doctor->toArray();

            }
        }

        $response = new JsonResponse($doctorList, 200);

        return $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
