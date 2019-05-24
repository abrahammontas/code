<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DispatchOrder extends Controller
{
    public function __invoke($shopperId, $deliveryDate)
    {
        $em = $this->getDoctrine()->getManager();
        $orders = $em->getRepository(Order::class)->findByShopperAndDate($shopperId, $deliveryDate);

        return [
            'status' => 'OK',
            'description' => 'Return orders filter by shopper and date!',
            'data' => $orders,
        ];
    }
}
