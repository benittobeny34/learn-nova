<?php

namespace App\Nova;

use App\Nova\Filters\PostPublished;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;

class Post extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Post::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title', 'body'
    ];

    public static $globallySearchable = true;

    public function title()
    {
        return $this->title . '-' . $this->category;
    }

    public function subtitle()
    {
        return 'Author: ' . $this->user->name;
    }

    //override the indexquery method and customize the however you want
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query;
//        return $query->where('user_id', $request->user()->id);
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Text::make('Title')->rules(['required', 'min:5', 'max:256']),
            Trix::make('Body')->rules('required'),
            DateTime::make('Publish At', 'publish_at')->rules('after_or_equal:today'),
            DateTime::make('Publish Until', 'publish_until')->rules('after_or_equal:publish_at'),
            Boolean::make('Publish', 'is_published')
                ->canSee(function ($request) {
//                return $request->user()->can('publish_post');
                    return true;
                }),
            Select::make('Category')->options([
                'tutorials' => 'Tutorials',
                'news' => 'News',
                'sports' => 'Sports',
            ])->hideWhenUpdating()->rules('required'),

            BelongsTo::make('User')->rules('required'),
            BelongsToMany::make('Tags'),
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
        return [
            new PostPublished()
        ];
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
}
