<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ImportController
{
    #[Route('/')]
    public function number(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>'.$number.'</body></html>'
        );
    }
}