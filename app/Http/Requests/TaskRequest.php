<?php

namespace App\Http\Requests;

use App\Filters\UppercaseFirstFilter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Waavi\Sanitizer\Laravel\SanitizesInput;

/**
 * Class TaskRequest
 *
 * @package App\Http\Requests
 */
class TaskRequest extends FormRequest
{
    use SanitizesInput;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        switch ($this->method()) {
            case 'POST': {
                return auth()->check();
            }
            case 'PATCH':
            case 'DELETE': {
                return Gate::allows('update', $this->task);
            }
            default: return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
            case 'PATCH': {
                return [
                    'name' => 'required|min:3|max:255',
                    'card_id' => 'required|exists:cards,id',
                ];
            }
            case 'DELETE':
            default: return [];
        }
    }

    /**
     * @return array
     */
    public function validated()
    {
        $validData = parent::validated();

        return $validData + ['creator_id' => auth()->id()];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Task name is required!',
        ];
    }

    /**
     *  Filters to be applied to the input, because of SanitizesInput trait
     *
     * @return array
     */
    public function filters()
    {
        return [
            'name' => 'escape|ucfirst:noLower',
        ];
    }

    /**
     * Custom filter created
     *
     * @return array
     */
    public function customFilters()
    {
        return [
            'ucfirst' => UppercaseFirstFilter::class
        ];
    }
}
