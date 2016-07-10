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
use Example\AppBundle\Entity\Shedule;


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

    /**
     *
     * Pаcписания врача
     *
     * @param Request $request
     * @param $id
     *
     * @ApiDoc(
     *  section="Api",
     *  statusCodes={
     *      200="ok",
     *      400="internal Error",
     *  }
     * )
     *
     * @Get("/doctor/{id}/shedule")
     *
     * @return JsonResponse
     */
    public function getShedule(Request $request, $id)
    {
        $periond = $this->container->getParameter('shedule_preriod');

        // TODO тут нужна функция обработки переходных периодов
        $currentWeek = date('W');
        $currentYear = date('Y');
        $nextWeek = $currentWeek + $periond;

        $shedules = $this->getDoctrine()->getManager('example')
            ->getRepository('ExampleAppBundle:Shedule')
            ->getSheduleByPeriod($id, $currentYear, $currentWeek, $nextWeek);

        $sheduleList = [];

        if ($shedules) {
            foreach ($shedules as $shedule) {

                $sheduleList[] = $shedule->toArray();

            }
        }

        return new JsonResponse($sheduleList, 200);

    }

    /**
     * Обновление записи о расписании
     *
     * @param Request $request
     * @param $id
     *
     * @ApiDoc(
     *  section="Api",
     *  statusCodes={
     *      200="ok",
     *      422="Error",
     *  },
     *  parameters={
     *    {"name"="day", "dataType"="string", "required"=true, "description"="День"},
     *    {"name"="start", "dataType"="string", "required"=true, "description"="Начало приема"},
     * }
     * )
     *
     * @Post("/shedule/{id}")
     *
     * @return JsonResponse
     */
    public function updateShedule(Request $request, $id)
    {

        $day = $request->request->get('day', 'не указан день');
        $start = $request->request->get('start', 'нет периода');

        $shedule = $this->getDoctrine()->getManager('example')
            ->getRepository('ExampleAppBundle:Shedule')
            ->find($id);

        if (!$shedule) {
            return new JsonResponse(['message' => 'расписание не найдено'], 422);
        }

        // TODO userID mockup only
        $result = $this->getDoctrine()->getManager('example')
            ->getRepository('ExampleAppBundle:Shedule')
            ->updateShedule($shedule, 1, $day, $start);

        if (!$result) {
            return new JsonResponse(['message' => 'занято, выберите другой период'], 422);
        }

        return new JsonResponse($result, 200);
    }
}
