<?php

namespace App\Rules;

use App\Models\Genre;
use Illuminate\Contracts\Validation\Rule;


class ExistsGenres implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $values)
    {
        // dd($values);
        $values = explode(',', $values);

        if ($values != [] && $values != null) {
            foreach($values as $value) {
                $finded = Genre::find($value);
                if ($finded == null) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Genres id given are not in the database or the parameter synatax is wrong. Must be genre id followed by comma. Example: 4,6,2,1';
    }
}
