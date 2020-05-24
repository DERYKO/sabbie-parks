<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Collection extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $group = 'Payments';

    public static $model = 'App\Collection';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

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
            Text::make('Receipt No'),
            BelongsTo::make('UserVehicle', 'user_vehicle'),
            BelongsTo::make('Client'),
            BelongsTo::make('ParkingSpot', 'parking_spot')
                ->rules('required'),
            Text::make('Payment Type'),
            Text::make('Request Id', 'merchantRequestId')->onlyOnDetail(),
            Text::make('Checkout Id', 'checkoutRequestId')->onlyOnDetail(),
            Text::make('Amount'),
            Text::make('Party A', 'partyA')->onlyOnDetail(),
            Text::make('Party B', 'partyB')->onlyOnDetail(),
            Boolean::make('Status'),
            DateTime::make('Date', 'created_at')
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

    public static function indexQuery(NovaRequest $request, $query)
    {
       return $query->where('client_id', $request->user()->client_id);

    }

    public static function label()
    {
        return 'Collection';
    }
}
