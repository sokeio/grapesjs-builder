<?php

namespace SokeioPlugin\BuilderPage\Livewire;

use Sokeio\Builder\WithPageBuilder;
use Sokeio\Component;
use Sokeio\Admin\Item;
use Sokeio\Admin\ItemManager;
use SokeioPlugin\BuilderPage\Models\PageBuilder as PageBuilderModel;
use Carbon\Carbon;

class PageBuilder extends Component
{
    use WithPageBuilder;
    protected function getLinkPageList()
    {
        return route('admin.page-builder-list');
    }
    protected function ItemManager()
    {
        return ItemManager::Form()->Model(PageBuilderModel::class)->Item([
            Item::Add('name')->Column(Item::Col12)->Title('Title')->Required(),
            Item::Add('slug')->Column(Item::Col12)->Title(function () {
                if (env('SOKEIO_SUB_DOMAIN')) {
                    return 'Subdomain';
                }
                return 'Slug';
            })->Type('subdomain'),
            Item::Add('description')->Column(Item::Col12)->Type('textarea')->Title('Description'),
            Item::Add('image')->Column(Item::Col12)->Type('images')->Title('Featured image'),
            Item::Add('custom_css')->Title('Css')->Type('textarea')->Column(Item::Col12),
            Item::Add('custom_js')->Title('Js')->Type('textarea')->Column(Item::Col12),
            Item::Add('css')->InputHidden(),
            Item::Add('js')->InputHidden(),
            Item::Add('seo_id')->InputHidden()->NoBindData()->FieldData(function ($data, $item) {
                return $data->seo->first()->id;
            }),
            Item::Add('published_at')->Column(Item::Col12)->Type('flatpickr')->ValueDefault(function () {
                return Carbon::now();
            })->Title('Published At'),
            Item::Add('status')->Title('Status')->DataOptionStatus()->Column(Item::Col12)->ValueDefault(function () {
                return 1;
            }),
            Item::Add('content')->Column(Item::Col12)->InputHidden(),
            Item::Add('author_id')->Column(Item::Col12)->InputHidden()->ValueDefault(function () {
                return auth()->user()->id;
            }),

        ])->BeforeQuery(function ($query) {
            return $query->with('seo');
        })
            ->BeforeSave(function ($model) {
                $model->author_id = auth()->user()->id;
                return $model;
            });
    }
    public function doSaveBuilder()
    {
        try {
            $model = $this->form->DataFromForm();
            if (!$this->dataId) {
                $this->showMessage('Save Data Successfull');
                return redirect(route('admin.page-builder-edit', ['dataId' => $model->id]));
            } else {
                $this->showMessage('Save Data Successfull');
            }
        } catch (\Exception $ex) {
            $this->tabIndex = 2;
            throw $ex;
        }
    }
}
