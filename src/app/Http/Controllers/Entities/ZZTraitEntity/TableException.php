<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use Illuminate\Validation\ValidationException;

class TableException extends \Exception
{
    /**
     * @var array
     */
    private $errors;

    /**
     * TableException constructor.
     * @param array $errors
     */
    public function __construct(ValidationException $e, $request)
    {
        // dump($e);
        $messageBag = $e->validator->getMessageBag();
        $messages = $messageBag->getMessages();
        $tableName = $request['tableName'];
        $table01Name = $request['table01Name'];

        dump($tableName, $table01Name,  $messages);
        return redirect("")->withErrors($e->validator)->withInput();
    }
}
