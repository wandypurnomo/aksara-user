<?php

namespace Plugins\User\Presenters;

use Aksara\SimpleListTable\BasePresenter;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * This class handles how we present the table, the data is already filtered
 * before passed to constructor. Keep in mind that this example uses Laravel's
 * Paginator, if you want to use another method, it is possible as long as
 * the returned data is in same specification. You can try dump variables in
 * this class's instance to get the idea of the API.
 */
class UserListTable extends BasePresenter
{
    private $userPaginator;

    public function __construct(
        LengthAwarePaginator $userPaginator,
        Request $request
    ) {
        $this->userPaginator = $userPaginator;
        parent::__construct($request);
    }

    /**
     * required
     */
    public function paginationArgs()
    {
        return [
            'first' => $this->userPaginator->firstItem(),
            'last' => $this->userPaginator->lastItem(),
            'total' => $this->userPaginator->total(),
            'links' => $this->userPaginator->appends(
                $this->request->except('page'))->links(),
        ];
    }

    protected function columns()
    {
        /**
         * the order of columns key here determines the display order
         */
        return [
            //custom column, value should be defined in columnDefaults
            'check_column' => [
                'attributes' => [
                    'class' => 'text-center',
                    'width' => '20',
                ],
                'inner_html' => '<input type="checkbox">',
            ],
            //data columns, exists in items field
            'name' => __('user::labels.user_name'),
            'email' => __('user::labels.email'),
            'active' => __('user::labels.active'),
            //custom column, value should be defined in columnDefaults
            'actions' => [
                'attributes' => [
                    'class' => 'text-center',
                    'width' => 80
                ],
                'inner_html' => __('user::labels.action'),
            ]
        ];
    }

    protected function columnDefaults($item)
    {
        return [
            'check_column' =>
                '<div class="text-center" width=30>
                    <input name="id[]" type="checkbox" value="'.$item['id'].'">
                </div>'
            ,
            'actions' => view('user::user.row-action', [
                'edit_url' => route('aksara-user-edit', $item['id']),
                'delete_url' => route('aksara-user-destroy', $item['id']),
            ])->render(),
            'active' => $item['active'] ? __('user::labels.active') :
                __('user::labels.inactive'),
        ];
    }

    protected function items()
    {
        //use assoc array for current item in page
        return array_map(function ($item) {
            $item = $item->toArray();
            return $item;
        }, $this->userPaginator->items());
    }

    /**
     * endrequired
     */

    protected function sortable()
    {
        return [
            'name',
            'email',
        ];
    }

    protected function bulkActionItems()
    {
        $bulkActions = [];

        if (has_capability('delete-user')) {
            $bulkActions['destroy'] = __('user::labels.delete');
        }
        return $bulkActions;
    }

    protected function views()
    {
        return [
            'status' => [
                '' => __('user::labels.all'),
                'active' => __('user::labels.active'),
                'inactive' => __('user::labels.inactive'),
            ],
        ];
    }

    public function filters()//returns html
    {
        $search = $this->request->input('search');
        $statuses = [
            '0' => __('user::labels.inactive'),
            '1' => __('user::labels.active'),
        ];
        $status_selected = $this->request->input('is_active');

        return view('user::user.table-filter', compact('search',
            'statuses', 'status_selected'))
            ->render();
    }
}
