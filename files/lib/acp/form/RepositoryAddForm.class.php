<?php

namespace packages\acp\form;

use packages\data\repository\RepositoryAction;
use packages\data\repository\RepositoryList;
use wcf\form\AbstractFormBuilderForm;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\TextFormField;
use wcf\system\form\builder\field\validation\FormFieldValidationError;
use wcf\system\form\builder\field\validation\FormFieldValidator;

class RepositoryAddForm extends AbstractFormBuilderForm
{
    /**
     * @inheritDoc
     */
    public $objectActionClass = RepositoryAction::class;

    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'packages.acp.menu.link.package.repository.add';

    /**
     * @inheritDoc
     */
    protected function createForm()
    {
        parent::createForm();

        $this->form->appendChildren([
            FormContainer::create('data')
                ->appendChildren([
                    TextFormField::create('name')
                        ->label('packages.page.repositoryAdd.name')
                        ->description('packages.page.repositoryAdd.name.description')
                        ->required()
                        ->maximumLength(20)
                        ->minimumLength(2)
                        ->addValidator(
                            new FormFieldValidator('formatCheck', static function (TextFormField $formField) {
                                if (\preg_match('/^[0-9]+$/', \substr($formField->getValue(), 0, 1))) {
                                    $formField->addValidationError(
                                        new FormFieldValidationError(
                                            'noNumberOnStart',
                                            'packages.page.repositoryAdd.name.error.noNumberOnStart'
                                        )
                                    );

                                    return;
                                }
                                if (!\preg_match('/^[a-z0-9]+$/', $formField->getValue())) {
                                    $formField->addValidationError(
                                        new FormFieldValidationError(
                                            'wrongFormat',
                                            'packages.page.repositoryAdd.name.error.wrongFormat'
                                        )
                                    );

                                    return;
                                }

                                $repositoryList = new RepositoryList();
                                $repositoryList->getConditionBuilder()->add('name = ?', [$formField->getValue()]);
                                $repositoryList->readObjects();
                                if (\count($repositoryList) > 0) {
                                    $formField->addValidationError(
                                        new FormFieldValidationError(
                                            'alreadyUsed',
                                            'packages.page.repositoryAdd.name.error.alreadyUsed'
                                        )
                                    );
                                }
                            })
                        ),
                ]),
        ]);
    }
}
