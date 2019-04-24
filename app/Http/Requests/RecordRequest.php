<?php

namespace App\Http\Requests;

use App\Filters\UppercaseFirstFilter;
use App\Rules\ValidCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Waavi\Sanitizer\Laravel\SanitizesInput;


/**
 * Class RecordRequest
 *
 * @package App\Http\Requests
 */
class RecordRequest extends FormRequest
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
            case 'POST':
                {
                    return auth()->check();
                }

            case 'PATCH':
            case 'DELETE':
                {
                    return Gate::allows('update', $this->record);
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
            case 'PATCH':
                {
                    return [
                        'time_start' => 'required|date',
                        'time_end' => 'nullable|date',
                        'description' => 'required|min:3|max:255',
                        'category_id' => ['required', new ValidCategory()],
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

        return $validData + ['user_id' => auth()->id()];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [];
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
