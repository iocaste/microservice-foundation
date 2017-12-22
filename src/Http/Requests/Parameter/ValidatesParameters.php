<?php

namespace Iocaste\Microservice\Foundation\Http\Requests\Parameter;

use Iocaste\Microservice\Foundation\Data\Models\Parameter\Parameter;
use Iocaste\Microservice\Foundation\Http\Requests\Request;

/**
 * Class ValidatesParameters.
 */
abstract class ValidatesParameters extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $input = array_keys($this->request->all());

        return Parameter::whereIn('id', $input)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item['id'] => $item->rules];
            })->all();
    }
}
