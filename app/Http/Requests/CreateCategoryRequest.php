<?php

namespace App\Http\Requests;

use App\Filters\UppercaseFirstFilter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use Waavi\Sanitizer\Laravel\SanitizesInput;

class CreateCategoryRequest extends FormRequest
{
    use SanitizesInput;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'min:3',
                'max:255',
                Rule::unique('categories')->where('user_id', auth()->id()),
            ]
        ];
    }

    /**
     * Get validated params
     *
     * @return array
     */
    public function validated()
    {
        $validData = parent::validated();

        return $validData + ['user_id' => auth()->id()];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Category name is required!',
            'name.unique' => 'Category already exists!',
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
            'name' => 'escape|ucfirst'
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
