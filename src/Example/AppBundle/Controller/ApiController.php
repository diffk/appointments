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
use \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Example\AppBundle\Common\Time;


/**
 * Class ApiController
 *
 * @package Example\AppBundle\Controller
 */
class ApiController extends Controller
{
    use Time;

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
     *
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    public function getDoctorList()
    {
        $doctors = $this->getDoctrine()->getManager('example')
                                       ->getRepository('ExampleAppBundle:Doctor')->findAll();

        $doctorList = [];

        if ($doctors) {
            /** @var Doctor $doctor */
            foreach ($doctors as $doctor) {
                $doctorList[] = $doctor->toArray();
            }
        }

        $response = new JsonResponse($doctorList, Response::HTTP_OK);

        return $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    /**
     *
     * Pаcписание врача
     *
     * @param Request $request
     * @param int     $id
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
     *
     * @throws \LogicException
     * @throws \InvalidArgumentException
     * @throws InvalidArgumentException
     */
    public function getShedule(Request $request, $id)
    {
        $periond = $this->container->getParameter('shedule_preriod');

        // TODO тут нужна функция обработки переходных периодов
        $currentWeek = date('W');
        $currentYear = date('Y');
        $nextWeek = $currentWeek + $periond;

        $schedules = $this->getDoctrine()
                         ->getManager('example')
                         ->getRepository('ExampleAppBundle:Shedule')
                         ->getSheduleByPeriod($id, $currentYear, $currentWeek, $nextWeek);

        $sheduleList = [];

        if ($schedules) {
            foreach ($schedules as $shedule) {
                $record = $shedule->toArray();
                $record['period'] = $this->getDatesByWeek($record['week'], $record['year']);
                $sheduleList[] = $record;
            }
        }

        $sheduleList = $this->filteredDays($sheduleList);

        return new JsonResponse($sheduleList, Response::HTTP_OK);
    }

    /**
     * Обновление записи о расписании
     *
     * @param Request $request
     * @param         $id
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
     *
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    public function updateSheduleAction(Request $request, $id)
    {
        $day = $request->request->get('day', 'не указан день');
        $start = $request->request->get('start', 'нет периода');

        $schedule = $this->getDoctrine()->getManager('example')
                                       ->getRepository('ExampleAppBundle:Shedule')->find($id);

        if (!$schedule) {
            return new JsonResponse(['message' => 'расписание не найдено'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // TODO userID mockup only
        $result = $this->getDoctrine()->getManager('example')
                                      ->getRepository('ExampleAppBundle:Shedule')->updateShedule(
                $schedule,
                1,
                $day,
                $start
            );

        if (!$result) {
            return new JsonResponse(['message' => 'занято, выберите другой период'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $message = sprintf(
            'Вы записаны на прием к специалисту %s,
            дата посещения: %s,
            время приема %s',
            $schedule->getDoctor()->getName(),
            $this->getDateM($schedule->getWeek(), $schedule->getYear(), $day),
            $start
        );

        return new JsonResponse(['message' => $message], Response::HTTP_OK);
    }
}
