<?php

namespace App\Http\Requests;

use App\Filters\UppercaseFirstFilter;
use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

/**
 * Class CardRequest
 *
 * @package App\Http\Requests
 */
class CardRequest extends FormRequest
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
            case 'DELETE':
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
                    'description' => 'nullable|present|min:3|max:255'
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
            'name.required' => 'Card name is required!',
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
            'name' => 'escape|ucfirst',
            'description' => 'escape|ucfirst:noLower|trim',
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
