<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Libs\Gitlab\Client;
use Nette;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    public function actionDefault(int $topGroupId=10975505)
    {
        $this->client->getAccesses($topGroupId);
        $this->client->test($topGroupId);
        /*bdump('1');
        $this->client->getAccesses(10975587);
        bdump('2');
        $this->client->getAccesses(10975598);
        bdump('3');
        $this->client->getAccesses(10975610);
        bdump('4');
        $this->client->getAccesses(10975599);*/
        //$this->sendJson($this->client->getAccesses($topGroupId));
    }
}
