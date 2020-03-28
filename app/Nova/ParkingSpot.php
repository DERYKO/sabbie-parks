<?php

namespace App\Nova;

use Deryko\MappingSelect\MappingSelect;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class ParkingSpot extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $group = 'Parking';

    public static $model = 'App\ParkingSpot';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'parking_spot_code';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];


    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Client')
                ->rules('required'),
            Text::make('Parking Code', 'parking_spot_code')
                ->rules('required'),
            Select::make('Status')->options([
                'booked' => 'Booked',
                'occupied' => 'Occupied',
                'vacant' => 'Vacant',
                'reserved' => 'Reserved',
            ])->rules('required'),
            Text::make('Latitude')->onlyOnDetail(),
            Text::make('Longitude')->onlyOnDetail(),
            Text::make('Land Mark')->onlyOnDetail(),
            BelongsTo::make('Location')
                ->rules('required'),
            MappingSelect::make('Mapping')->onlyOnForms(),
            HasOne::make('Pricing')
                ->rules('required'),
            HasMany::make('Allowed'),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    public static function label()
    {
        return 'Parking Spaces';
    }
}
