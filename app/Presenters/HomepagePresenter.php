<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Libs\Gitlab\Client;
use Nette;
use Nette\Application\UI\Form;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{
    const DEFAULT_GROUP_ID = 10975505;
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    public function actionResult(int $id)
    {
        $this->template->gitlabUsers = $this->client->getUsersByToGroupId($id);
    }

    public function createComponentGitlabForm()
    {
        $form = new Form();
        $form->addInteger('topGroupId', 'Top group id')
            ->setRequired(true);
        $form->addSubmit('send', 'Odeslat');
        $form->onSuccess[] = [$this, 'formSucceeded'];

        return $form;
    }

    public function formSucceeded(Form $form, $data): void
    {
        $this->redirect('Homepage:result', ['id' => $data['topGroupId']]);
    }
}
