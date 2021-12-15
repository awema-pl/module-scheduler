<?php

namespace AwemaPL\Scheduler\User\Sections\Schedules\Http\Requests;

use AwemaPL\Scheduler\Sections\Options\Models\Option;
use AwemaPL\Scheduler\User\Sections\Schedules\Http\Requests\Rules\ValidCron;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSchedule extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validCron = app(ValidCron::class);
        return [
            'name' => 'required|string|max:255',
            'cron' => ['required', 'string', 'max:255', $validCron],
            'activated' => 'boolean',
        ];
    }


    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => _p('scheduler::requests.user.schedule.attributes.name', 'Name'),
            'cron' => _p('scheduler::requests.user.schedule.attributes.url', 'cron'),
            'activated' => _p('scheduler::requests.user.schedule.attributes.api_key', 'activated'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
