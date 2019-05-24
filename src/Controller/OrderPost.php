<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Order;
use App\Entity\User;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrderPost extends Controller
{
    public function __invoke(Order $data)
    {
        $errors = $this->get('validator')->validate($data);

        if (count($errors) > 0) {
            return $errors;
        }
        
        //Faking logged user
        $em = $this->getDoctrine()->getManager();
        $userLogged = $em->getRepository(User::class)->getRandomClient();

        $data->setOwner($userLogged);

        $em->persist($data);
        $em->flush();

        return [
            'status' => 'OK',
            'description' => 'Return a saved order!',
            'data' => $data,
        ];
    }
}
