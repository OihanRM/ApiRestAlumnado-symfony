<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use App\Repository\AlumnadoRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Alumnado;



class AlumnadoController extends AbstractController
{
    #[Route('/ws/alumnado', name: 'app_alumnado', methods: ['GET'])]
    public function index(AlumnadoRepository $alumnadoRepository): Response
    {
        return $this->convertToJson($alumnadoRepository->findAll());
    }

    #[Route('/ws/alumnado/add', name: 'app_alumnado_add', methods: ['POST'])]
    public function addAlumnado(AlumnadoRepository $alumnadoRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if(empty($data)){
            throw new NotFoundHttpException('No data found');
        }
        $alumno = new Alumnado($data['dni'], $data['nombre'], $data['apellido1'], $data['apellido2'], new \DateTime($data['fecha']), $data['provincia']);
        $alumnadoRepository->add($alumno, true);
        return new JsonResponse(['status' => 'Alumno creado!'], Response::HTTP_CREATED);
    }
    #[Route('/ws/alumnado/delete/{id}', name: 'app_alumnado_delete', methods: ['DELETE'])]
    public function deleteAlumnado(AlumnadoRepository $alumnadoRepository, $id): JsonResponse
    {
        $alumno = $alumnadoRepository->findOneBy(['id' => $id]);
        if(empty($alumno)){
            throw new NotFoundHttpException('No data found');
        }
        $alumnadoRepository->delete($alumno);
        return new JsonResponse(['status' => 'Alumno fusilado!'], Response::HTTP_CREATED);

    }
    #[Route ('/ws/alumnado/update/{id}', name: 'app_alumnado_update', methods: ['PUT'])]
    public function updateAlumnado(AlumnadoRepository $alumnadoRepository, Request $request, $id): JsonResponse
    {
        $alumno = $alumnadoRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);
        if(empty($alumno)){
            throw new NotFoundHttpException('No data found');
        }
        //leave data as is if not set
        $alumno->setDni($data['dni'] ?? $alumno->getDni());
        $alumno->setNombre($data['nombre'] ?? $alumno->getNombre());
        $alumno->setApellido1($data['apellido1'] ?? $alumno->getApellido1());
        $alumno->setApellido2($data['apellido2'] ?? $alumno->getApellido2());
        $alumno->setFecha(new \DateTime($data['fecha']) ?? $alumno->getFecha());
        $alumno->setProvincia($data['provincia'] ?? $alumno->getProvincia());
        $alumnadoRepository->add($alumno);
        return new JsonResponse(['status' => 'Alumno actualizado!'], Response::HTTP_CREATED);
    }

    private function convertToJson($object):JsonResponse
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $normalized = $serializer->normalize($object, null, array(DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'));
        $jsonContent = $serializer->serialize($normalized, 'json');
        return JsonResponse::fromJsonString($jsonContent, 200);
    }
}
