<?php

namespace warm\admin\renderer\expand;

use warm\admin\renderer\Action;
use warm\admin\renderer\Alert;
use warm\admin\renderer\AnchorNav;
use warm\admin\renderer\App;
use warm\admin\renderer\Audio;
use warm\admin\renderer\Avatar;
use warm\admin\renderer\Badge;
use warm\admin\renderer\Barcode;
use warm\admin\renderer\Breadcrumb;
use warm\admin\renderer\Button;
use warm\admin\renderer\ButtonGroup;
use warm\admin\renderer\Calendar;
use warm\admin\renderer\Card;
use warm\admin\renderer\Cards;
use warm\admin\renderer\Carousel;
use warm\admin\renderer\Chart;
use warm\admin\renderer\Code;
use warm\admin\renderer\Collapse;
use warm\admin\renderer\Color;
use warm\admin\renderer\Container;
use warm\admin\renderer\CRUD;
use warm\admin\renderer\Custom;
use warm\admin\renderer\Date;
use warm\admin\renderer\Dialog;
use warm\admin\renderer\Divider;
use warm\admin\renderer\Drawer;
use warm\admin\renderer\DropdownButton;
use warm\admin\renderer\Each;
use warm\admin\renderer\Flex;
use warm\admin\renderer\form\ButtonGroupSelect;
use warm\admin\renderer\form\ButtonToolbar;
use warm\admin\renderer\form\ChainedSelect;
use warm\admin\renderer\form\ChartRadios;
use warm\admin\renderer\form\Checkbox;
use warm\admin\renderer\form\Checkboxes;
use warm\admin\renderer\form\Combo;
use warm\admin\renderer\form\ConditionBuilder;
use warm\admin\renderer\form\Control;
use warm\admin\renderer\form\DiffEditor;
use warm\admin\renderer\form\Editor;
use warm\admin\renderer\form\Fieldset;
use warm\admin\renderer\form\Form;
use warm\admin\renderer\form\Formula;
use warm\admin\renderer\form\Group;
use warm\admin\renderer\form\Hidden;
use warm\admin\renderer\form\InputArray;
use warm\admin\renderer\form\InputCity;
use warm\admin\renderer\form\InputColor;
use warm\admin\renderer\form\InputDate;
use warm\admin\renderer\form\InputDateRange;
use warm\admin\renderer\form\InputDatetime;
use warm\admin\renderer\form\InputDatetimeRange;
use warm\admin\renderer\form\InputExcel;
use warm\admin\renderer\form\InputFile;
use warm\admin\renderer\form\InputFormula;
use warm\admin\renderer\form\InputGroup;
use warm\admin\renderer\form\InputImage;
use warm\admin\renderer\form\InputKv;
use warm\admin\renderer\form\InputKvs;
use warm\admin\renderer\form\InputMonth;
use warm\admin\renderer\form\InputMonthRange;
use warm\admin\renderer\form\InputNumber;
use warm\admin\renderer\form\InputPassword;
use warm\admin\renderer\form\InputQuarter;
use warm\admin\renderer\form\InputQuarterRange;
use warm\admin\renderer\form\InputRange;
use warm\admin\renderer\form\InputRating;
use warm\admin\renderer\form\InputRepeat;
use warm\admin\renderer\form\InputRichText;
use warm\admin\renderer\form\InputSignature;
use warm\admin\renderer\form\InputSubForm;
use warm\admin\renderer\form\InputTable;
use warm\admin\renderer\form\InputTag;
use warm\admin\renderer\form\InputText;
use warm\admin\renderer\form\InputTime;
use warm\admin\renderer\form\InputTimeRange;
use warm\admin\renderer\form\InputTree;
use warm\admin\renderer\form\InputVerificationCode;
use warm\admin\renderer\form\InputYear;
use warm\admin\renderer\form\InputYearRange;
use warm\admin\renderer\form\JsonSchema;
use warm\admin\renderer\form\JsonSchemaEditor;
use warm\admin\renderer\form\ListSelect;
use warm\admin\renderer\form\LocationPicker;
use warm\admin\renderer\form\MatrixCheckboxes;
use warm\admin\renderer\form\NestedSelect;
use warm\admin\renderer\form\Picker;
use warm\admin\renderer\form\Radio;
use warm\admin\renderer\form\Select;
use warm\admin\renderer\form\StaticClass;
use warm\admin\renderer\form\SwitchClass;
use warm\admin\renderer\form\TabsTransfer;
use warm\admin\renderer\form\TabsTransferPicker;
use warm\admin\renderer\form\Textarea;
use warm\admin\renderer\form\Transfer;
use warm\admin\renderer\form\TransferPicker;
use warm\admin\renderer\form\TreeSelect;
use warm\admin\renderer\form\Uuid;
use warm\admin\renderer\Grid;
use warm\admin\renderer\Grid2d;
use warm\admin\renderer\GridNav;
use warm\admin\renderer\Hbox;
use warm\admin\renderer\Html;
use warm\admin\renderer\Icon;
use warm\admin\renderer\Iframe;
use warm\admin\renderer\Image;
use warm\admin\renderer\Images;
use warm\admin\renderer\Json;
use warm\admin\renderer\Link;
use warm\admin\renderer\ListClass;
use warm\admin\renderer\Log;
use warm\admin\renderer\Mapping;
use warm\admin\renderer\Markdown;
use warm\admin\renderer\Nav;
use warm\admin\renderer\Number;
use warm\admin\renderer\OfficeViewer;
use warm\admin\renderer\Page;
use warm\admin\renderer\Pagination;
use warm\admin\renderer\PaginationWrapper;
use warm\admin\renderer\Panel;
use warm\admin\renderer\PdfViewer;
use warm\admin\renderer\Popover;
use warm\admin\renderer\Portlet;
use warm\admin\renderer\Progress;
use warm\admin\renderer\Property;
use warm\admin\renderer\Qrcode;
use warm\admin\renderer\Radios;
use warm\admin\renderer\Remark;
use warm\admin\renderer\SearchBox;
use warm\admin\renderer\Service;
use warm\admin\renderer\Shape;
use warm\admin\renderer\Slider;
use warm\admin\renderer\Sparkline;
use warm\admin\renderer\Spinner;
use warm\admin\renderer\Status;
use warm\admin\renderer\Steps;
use warm\admin\renderer\SwitchContainer;
use warm\admin\renderer\Table;
use warm\admin\renderer\Table2;
use warm\admin\renderer\TableView;
use warm\admin\renderer\Tabs;
use warm\admin\renderer\Tag;
use warm\admin\renderer\Tasks;
use warm\admin\renderer\Timeline;
use warm\admin\renderer\Toast;
use warm\admin\renderer\TooltipWrapper;
use warm\admin\renderer\Tpl;
use warm\admin\renderer\Video;
use warm\admin\renderer\WebComponent;
use warm\admin\renderer\Wizard;
use warm\admin\renderer\Wrapper;

/**
 * Amis 构建器扩展类
 */
class Amis
{
    /**
     * 创建 Amis 构建器实例.
     *
     * @return self
     */
    public static function make(): static
    {
        return new self();
    }

    /**
     * 创建 Form 渲染器实例.
     *
     * @param string $name
     * @param string $label
     * @return Form
     */
    public function Form(string $name = '', string $label = ''): Form
    {
        $instance = Form::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Action 渲染器实例.
     *
     * @return Action
     */
    public function Action(): Action
    {
        return Action::make();
    }

    /**
     * 创建 Alert 渲染器实例.
     *
     * @return Alert
     */
    public function Alert(): Alert
    {
        return Alert::make();
    }

    /**
     * 创建 AnchorNav 渲染器实例.
     *
     * @return AnchorNav
     */
    public function AnchorNav(): AnchorNav
    {
        return AnchorNav::make();
    }

    /**
     * 创建 App 渲染器实例.
     *
     * @return App
     */
    public function App(): App
    {
        return App::make();
    }

    /**
     * 创建 Audio 渲染器实例.
     *
     * @return Audio
     */
    public function Audio(): Audio
    {
        return Audio::make();
    }

    /**
     * 创建 Avatar 渲染器实例.
     *
     * @return Avatar
     */
    public function Avatar(): Avatar
    {
        return Avatar::make();
    }

    /**
     * 创建 Badge 渲染器实例.
     *
     * @return Badge
     */
    public function Badge(): Badge
    {
        return Badge::make();
    }

    /**
     * 创建 Barcode 渲染器实例.
     *
     * @return Barcode
     */
    public function Barcode(): Barcode
    {
        return Barcode::make();
    }

    /**
     * 创建 Breadcrumb 渲染器实例.
     *
     * @return Breadcrumb
     */
    public function Breadcrumb(): Breadcrumb
    {
        return Breadcrumb::make();
    }

    /**
     * 创建 Button 渲染器实例.
     *
     * @return Button
     */
    public function Button(): Button
    {
        return Button::make();
    }

    /**
     * 创建 ButtonGroup 渲染器实例.
     *
     * @return ButtonGroup
     */
    public function ButtonGroup(): ButtonGroup
    {
        return ButtonGroup::make();
    }

    /**
     * 创建 ButtonGroupSelect 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return ButtonGroupSelect
     */
    public function ButtonGroupSelect(string $name = '', string $label = ''): ButtonGroupSelect
    {
        $instance = ButtonGroupSelect::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 ButtonToolbar 渲染器实例.
     *
     * @param string $name
     * @param string $label
     * @return ButtonToolbar
     */
    public function ButtonToolbar(string $name = '', string $label = ''): ButtonToolbar
    {
        $instance = ButtonToolbar::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Calendar
     */
    public function Calendar(): Calendar
    {
        return Calendar::make();
    }

    /**
     * @return Card
     */
    public function Card(): Card
    {
        return Card::make();
    }

    /**
     * @return Cards
     */
    public function Cards(): Cards
    {
        return Cards::make();
    }

    /**
     * @return Carousel
     */
    public function Carousel(): Carousel
    {
        return Carousel::make();
    }

    /**
     * 创建 ChainedSelect 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return ChainedSelect
     */
    public function ChainedSelect(string $name = '', string $label = ''): ChainedSelect
    {
        $instance = ChainedSelect::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Chart
     */
    public function Chart(): Chart
    {
        return Chart::make();
    }

    /**
     * @return ChartRadios
     */
    public function ChartRadios(): ChartRadios
    {
        return ChartRadios::make();
    }

    /**
     * 创建 Checkbox 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return Checkbox
     */
    public function Checkbox(string $name = '', string $label = ''): Checkbox
    {
        $instance = Checkbox::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Checkboxes 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return Checkboxes
     */
    public function Checkboxes(string $name = '', string $label = ''): Checkboxes
    {
        $instance = Checkboxes::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Code
     */
    public function Code(): Code
    {
        return Code::make();
    }

    /**
     * @return Collapse
     */
    public function Collapse(): Collapse
    {
        return Collapse::make();
    }

    /**
     * @return Color
     */
    public function Color(): Color
    {
        return Color::make();
    }

    /**
     * 创建 Combo 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return Combo
     */
    public function Combo(string $name = '', string $label = ''): Combo
    {
        $instance = Combo::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Component
     */
    public function Component(): Component
    {
        return Component::make();
    }

    /**
     * 创建 ConditionBuilder 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return ConditionBuilder
     */
    public function ConditionBuilder(string $name = '', string $label = ''): ConditionBuilder
    {
        $instance = ConditionBuilder::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Container
     */
    public function Container(): Container
    {
        return Container::make();
    }

    /**
     * @return Control
     */
    public function Control(): Control
    {
        return Control::make();
    }

    /**
     * @return CopyAction
     */
    public function CopyAction(): CopyAction
    {
        return CopyAction::make();
    }

    /**
     * @return CRUD
     */
    public function CRUD(): CRUD
    {
        return CRUD::make();
    }

    /**
     * @return Custom
     */
    public function Custom(): Custom
    {
        return Custom::make();
    }

    /**
     * @return CustomSvgIcon
     */
    public function CustomSvgIcon(): CustomSvgIcon
    {
        return CustomSvgIcon::make();
    }

    /**
     * 创建 CustomWangEditor 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return CustomWangEditor
     */
    public function CustomWangEditor(string $name = '', string $label = ''): CustomWangEditor
    {
        $instance = CustomWangEditor::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return CustomWatermark
     */
    public function CustomWatermark(): CustomWatermark
    {
        return CustomWatermark::make();
    }

    /**
     * @return Date
     */
    public function Date(): Date
    {
        return Date::make();
    }

    /**
     * @return Dialog
     */
    public function Dialog(): Dialog
    {
        return Dialog::make();
    }

    /**
     * 创建 DiffEditor 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return DiffEditor
     */
    public function DiffEditor(string $name = '', string $label = ''): DiffEditor
    {
        $instance = DiffEditor::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Divider
     */
    public function Divider(): Divider
    {
        return Divider::make();
    }

    /**
     * @return Drawer
     */
    public function Drawer(): Drawer
    {
        return Drawer::make();
    }

    /**
     * @return DropdownButton
     */
    public function DropdownButton(): DropdownButton
    {
        return DropdownButton::make();
    }

    /**
     * @return Each
     */
    public function Each(): Each
    {
        return Each::make();
    }

    /**
     * 创建 Editor 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return Editor
     */
    public function Editor(string $name = '', string $label = ''): Editor
    {
        $instance = Editor::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 FieldSet 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return Fieldset
     */
    public function Fieldset(string $name = '', string $label = ''): Fieldset
    {
        $instance = Fieldset::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Flex
     */
    public function Flex(): Flex
    {
        return Flex::make();
    }

    /**
     * 创建 Formula 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return Formula
     */
    public function Formula(string $name = '', string $label = ''): Formula
    {
        $instance = Formula::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Grid
     */
    public function Grid(): Grid
    {
        return Grid::make();
    }

    /**
     * @return Grid2d
     */
    public function Grid2d(): Grid2d
    {
        return Grid2d::make();
    }

    /**
     * @return GridNav
     */
    public function GridNav(): GridNav
    {
        return GridNav::make();
    }

    /**
     * 创建 Group 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return Group
     */
    public function Group(string $name = '', string $label = ''): Group
    {
        $instance = Group::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Hbox
     */
    public function Hbox(): Hbox
    {
        return Hbox::make();
    }

    /**
     * 创建 Hidden 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return Hidden
     */
    public function Hidden(string $name = '', string $label = ''): Hidden
    {
        $instance = Hidden::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Html
     */
    public function Html(): Html
    {
        return Html::make();
    }

    /**
     * @return Icon
     */
    public function Icon(): Icon
    {
        return Icon::make();
    }

    /**
     * @return Iframe
     */
    public function Iframe(): Iframe
    {
        return Iframe::make();
    }

    /**
     * 创建 Image 渲染器实例.
     *
     * @return Image
     */
    public function Image(): Image
    {
        return Image::make();
    }

    /**
     * 创建 Images 渲染器实例.
     *
     * @return Images
     */
    public function Images(): Images
    {
        return Images::make();
    }

    /**
     * 创建 InputArray 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputArray
     */
    public function InputArray(string $name = '', string $label = ''): InputArray
    {
        $instance = InputArray::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputCity 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputCity
     */
    public function InputCity(string $name = '', string $label = ''): InputCity
    {
        $instance = InputCity::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputColor 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputColor
     */
    public function InputColor(string $name = '', string $label = ''): InputColor
    {
        $instance = InputColor::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputDate 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputDate
     */
    public function InputDate(string $name = '', string $label = ''): InputDate
    {
        $instance = InputDate::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputDateRange 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputDateRange
     */
    public function InputDateRange(string $name = '', string $label = ''): InputDateRange
    {
        $instance = InputDateRange::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputDateTime 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputDatetime
     */
    public function InputDatetime(string $name = '', string $label = ''): InputDatetime
    {
        $instance = InputDatetime::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputDatetimeRange 渲染器实例.
     *
     * @param string $name
     * @param string $label
     * @return InputDatetimeRange
     */
    public function InputDatetimeRange(string $name = '', string $label = ''): InputDatetimeRange
    {
        $instance = InputDatetimeRange::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return InputExcel
     */
    public function InputExcel(): InputExcel
    {
        return InputExcel::make();
    }

    /**
     * 创建 InputFile 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputFile
     */
    public function InputFile(string $name = '', string $label = ''): InputFile
    {
        $instance = InputFile::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return InputFormula
     */
    public function InputFormula(): InputFormula
    {
        return InputFormula::make();
    }

    /**
     * 创建 InputGroup 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputGroup
     */
    public function InputGroup(string $name = '', string $label = ''): InputGroup
    {
        $instance = InputGroup::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputImage 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputImage
     */
    public function InputImage(string $name = '', string $label = ''): InputImage
    {
        $instance = InputImage::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return InputKv
     */
    public function InputKv(): InputKv
    {
        return InputKv::make();
    }

    /**
     * @return InputKvs
     */
    public function InputKvs(): InputKvs
    {
        return InputKvs::make();
    }

    /**
     * 创建 InputMonth 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputMonth
     */
    public function InputMonth(string $name = '', string $label = ''): InputMonth
    {
        $instance = InputMonth::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputMonthRange 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputMonthRange
     */
    public function InputMonthRange(string $name = '', string $label = ''): InputMonthRange
    {
        $instance = InputMonthRange::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputNumber 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputNumber
     */
    public function InputNumber(string $name = '', string $label = ''): InputNumber
    {
        $instance = InputNumber::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputPassword 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputPassword
     */
    public function InputPassword(string $name = '', string $label = ''): InputPassword
    {
        $instance = InputPassword::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputQuarter 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputQuarter
     */
    public function InputQuarter(string $name = '', string $label = ''): InputQuarter
    {
        $instance = InputQuarter::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputQuarterRange 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputQuarterRange
     */
    public function InputQuarterRange(string $name = '', string $label = ''): InputQuarterRange
    {
        $instance = InputQuarterRange::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputRange 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputRange
     */
    public function InputRange(string $name = '', string $label = ''): InputRange
    {
        $instance = InputRange::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputRating 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputRating
     */
    public function InputRating(string $name = '', string $label = ''): InputRating
    {
        $instance = InputRating::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputRepeat 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputRepeat
     */
    public function InputRepeat(string $name = '', string $label = ''): InputRepeat
    {
        $instance = InputRepeat::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputRichText 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputRichText
     */
    public function InputRichText(string $name = '', string $label = ''): InputRichText
    {
        $instance = InputRichText::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputSignature 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputSignature
     */
    public function InputSignature(string $name = '', string $label = ''): InputSignature
    {
        $instance = InputSignature::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputSubForm 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputSubForm
     */
    public function InputSubForm(string $name = '', string $label = ''): InputSubForm
    {
        $instance = InputSubForm::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return InputTable
     */
    public function InputTable(): InputTable
    {
        return InputTable::make();
    }

    /**
     * 创建 InputTag 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputTag
     */
    public function InputTag(string $name = '', string $label = ''): InputTag
    {
        $instance = InputTag::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputText 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputText
     */
    public function InputText(string $name = '', string $label = ''): InputText
    {
        $instance = InputText::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputTime 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputTime
     */
    public function InputTime(string $name = '', string $label = ''): InputTime
    {
        $instance = InputTime::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputTimeRange 渲染器实例.
     *
     * @param string $name
     * @param string $label
     * @return InputTimeRange
     */
    public function InputTimeRange(string $name = '', string $label = ''): InputTimeRange
    {
        $instance = InputTimeRange::make();
        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputTree 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputTree
     */
    public function InputTree(string $name = '', string $label = ''): InputTree
    {
        $instance = InputTree::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputVerificationCode 渲染器实例.
     *
     * @param string $name
     * @return InputVerificationCode
     */
    public function InputVerificationCode(string $name = ''): InputVerificationCode
    {
        $instance = InputVerificationCode::make();
        if ($name !== '') {
            $instance->name($name);
        }
        return $instance;
    }

    /**
     * 创建 InputYear 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return InputYear
     */
    public function InputYear(string $name = '', string $label = ''): InputYear
    {
        $instance = InputYear::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputYearRange 渲染器实例.
     *
     * @param string $name
     * @param string $label
     * @return InputYearRange
     */
    public function InputYearRange(string $name = '', string $label = ''): InputYearRange
    {
        $instance = InputYearRange::make();
        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Json
     */
    public function Json(): Json
    {
        return Json::make();
    }

    /**
     * 创建 JsonSchema 渲染器实例.
     *
     * @param string $name
     * @param string $label
     * @return JsonSchema
     */
    public function JsonSchema(string $name = '', string $label = ''): JsonSchema
    {
        $instance = JsonSchema::make();
        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 JSONSchemaEditor 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return JsonSchemaEditor
     */
    public function JsonSchemaEditor(string $name = '', string $label = ''): JsonSchemaEditor
    {
        $instance = JsonSchemaEditor::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Link
     */
    public function Link(): Link
    {
        return Link::make();
    }

    /**
     * @return ListClass
     */
    public function List(): ListClass
    {
        return ListClass::make();
    }

    /**
     * 创建 ListSelect 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return ListSelect
     */
    public function ListSelect(string $name = '', string $label = ''): ListSelect
    {
        $instance = ListSelect::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 LocationPicker 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return LocationPicker
     */
    public function LocationPicker(string $name = '', string $label = ''): LocationPicker
    {
        $instance = LocationPicker::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Log
     */
    public function Log(): Log
    {
        return Log::make();
    }

    /**
     * 创建 Mapping 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return Mapping
     */
    public function Mapping(string $name = '', string $label = ''): Mapping
    {
        $instance = Mapping::make();
        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Markdown
     */
    public function Markdown(): Markdown
    {
        return Markdown::make();
    }

    /**
     * 创建 MatrixCheckboxes 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return MatrixCheckboxes
     */
    public function MatrixCheckboxes(string $name = '', string $label = ''): MatrixCheckboxes
    {
        $instance = MatrixCheckboxes::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Nav
     */
    public function Nav(): Nav
    {
        return Nav::make();
    }

    /**
     * 创建 NestedSelect 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return NestedSelect
     */
    public function NestedSelect(string $name = '', string $label = ''): NestedSelect
    {
        $instance = NestedSelect::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Number
     */
    public function Number(): Number
    {
        return Number::make();
    }

    /**
     * @return OfficeViewer
     */
    public function OfficeViewer(): OfficeViewer
    {
        return OfficeViewer::make();
    }

    /**
     * @return Page
     */
    public function Page(): Page
    {
        return Page::make();
    }

    /**
     * @return Pagination
     */
    public function Pagination(): Pagination
    {
        return Pagination::make();
    }

    /**
     * @return PaginationWrapper
     */
    public function PaginationWrapper(): PaginationWrapper
    {
        return PaginationWrapper::make();
    }

    /**
     * @return Panel
     */
    public function Panel(): Panel
    {
        return Panel::make();
    }

    /**
     * @return PdfViewer
     */
    public function PdfViewer(): PdfViewer
    {
        return PdfViewer::make();
    }

    /**
     * 创建 Picker 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return Picker
     */
    public function Picker(string $name = '', string $label = ''): Picker
    {
        $instance = Picker::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Popover
     */
    public function Popover(): Popover
    {
        return Popover::make();
    }

    /**
     * @return Portlet
     */
    public function Portlet(): Portlet
    {
        return Portlet::make();
    }

    /**
     * 创建 Progress 渲染器实例.
     *
     * @param string $name
     * @param string $label
     * @return Progress
     */
    public function Progress(string $name = '', string $label = ''): Progress
    {
        $instance = Progress::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Property
     */
    public function Property(): Property
    {
        return Property::make();
    }

    /**
     * @return Qrcode
     */
    public function Qrcode(): Qrcode
    {
        return Qrcode::make();
    }

    /**
     * 创建 Radio 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return Radio
     */
    public function Radio(string $name = '', string $label = ''): Radio
    {
        $instance = Radio::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Radios 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return Radios
     */
    public function Radios(string $name = '', string $label = ''): Radios
    {
        $instance = Radios::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Remark
     */
    public function Remark(): Remark
    {
        return Remark::make();
    }

    /**
     * @return SearchBox
     */
    public function SearchBox(): SearchBox
    {
        return SearchBox::make();
    }

    /**
     * 创建 Select 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return Select
     */
    public function Select(string $name = '', string $label = ''): Select
    {
        $instance = Select::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Service
     */
    public function Service(): Service
    {
        return Service::make();
    }

    /**
     * @return Shape
     */
    public function Shape(): Shape
    {
        return Shape::make();
    }

    /**
     * @return Slider
     */
    public function Slider(): Slider
    {
        return Slider::make();
    }

    /**
     * @return Sparkline
     */
    public function Sparkline(): Sparkline
    {
        return Sparkline::make();
    }

    /**
     * @return Spinner
     */
    public function Spinner(): Spinner
    {
        return Spinner::make();
    }

    /**
     * 创建 StaticExactControl 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return StaticClass
     */
    public function Static(string $name = '', string $label = ''): StaticClass
    {
        $instance = StaticClass::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Status 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return Status
     */
    public function Status(string $name = '', string $label = ''): Status
    {
        $instance = Status::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Steps
     */
    public function Steps(): Steps
    {
        return Steps::make();
    }

    /**
     * 创建 SwitchClass 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return SwitchClass
     */
    public function Switch(string $name = '', string $label = ''): SwitchClass
    {
        $instance = SwitchClass::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return SwitchContainer
     */
    public function SwitchContainer(): SwitchContainer
    {
        return SwitchContainer::make();
    }

    /**
     * @return Table
     */
    public function Table(): Table
    {
        return Table::make();
    }

    /**
     * 创建 TableColumn 渲染器实例.
     *
     * @param string $name
     * @param string $label
     * @return TableColumn
     */
    public function TableColumn(string $name = '', string $label = ''): TableColumn
    {
        $instance = TableColumn::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Table2
     */
    public function Table2(): Table2
    {
        return Table2::make();
    }

    /**
     * @return TableView
     */
    public function TableView(): TableView
    {
        return TableView::make();
    }

    /**
     * @return Tabs
     */
    public function Tabs(): Tabs
    {
        return Tabs::make();
    }

    /**
     * 创建 TabsTransfer 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return TabsTransfer
     */
    public function TabsTransfer(string $name = '', string $label = ''): TabsTransfer
    {
        $instance = TabsTransfer::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 TabsTransferPicker 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return TabsTransferPicker
     */
    public function TabsTransferPicker(string $name = '', string $label = ''): TabsTransferPicker
    {
        $instance = TabsTransferPicker::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Tag
     */
    public function Tag(): Tag
    {
        return Tag::make();
    }

    /**
     * @return Tasks
     */
    public function Tasks(): Tasks
    {
        return Tasks::make();
    }

    /**
     * 创建 Textarea 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return Textarea
     */
    public function Textarea(string $name = '', string $label = ''): Textarea
    {
        $instance = Textarea::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Timeline
     */
    public function Timeline(): Timeline
    {
        return Timeline::make();
    }

    /**
     * @return Toast
     */
    public function Toast(): Toast
    {
        return Toast::make();
    }

    /**
     * @return TooltipWrapper
     */
    public function TooltipWrapper(): TooltipWrapper
    {
        return TooltipWrapper::make();
    }

    /**
     * @return Tpl
     */
    public function Tpl(): Tpl
    {
        return Tpl::make();
    }

    /**
     * 创建 Transfer 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return Transfer
     */
    public function Transfer(string $name = '', string $label = ''): Transfer
    {
        $instance = Transfer::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 TransferPicker 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return TransferPicker
     */
    public function TransferPicker(string $name = '', string $label = ''): TransferPicker
    {
        $instance = TransferPicker::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 TreeSelect 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return TreeSelect
     */
    public function TreeSelect(string $name = '', string $label = ''): TreeSelect
    {
        $instance = TreeSelect::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 UUID 渲染器实例.
     *
     * @param string $name 字段名
     * @param string $label 标签
     * @return Uuid
     */
    public function Uuid(string $name = '', string $label = ''): Uuid
    {
        $instance = Uuid::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * @return Video
     */
    public function Video(): Video
    {
        return Video::make();
    }

    /**
     * @return WebComponent
     */
    public function WebComponent(): WebComponent
    {
        return WebComponent::make();
    }

    /**
     * @return Wizard
     */
    public function Wizard(): Wizard
    {
        return Wizard::make();
    }

    /**
     * @return Wrapper
     */
    public function Wrapper(): Wrapper
    {
        return Wrapper::make();
    }

    /**
     * 创建 Operation 渲染器实例.
     *
     * @return Operation
     */
    public function Operation(): Operation
    {
        return Operation::make();
    }

//    /**
//     * @return EmailAction
//     */
//    public function EmailAction(): EmailAction
//    {
//        return EmailAction::make();
//    }
//
//
//    /**
//     * 创建 AjaxAction 渲染器实例.
//     *
//     * @return \warm\admin\renderer\expand\AjaxAction
//     */
//    public function AjaxAction(): \warm\admin\renderer\expand\AjaxAction
//    {
//        return \warm\admin\renderer\expand\AjaxAction::make();
//    }
//
//    /**
//     * @return DialogAction
//     */
//    public function DialogAction(): DialogAction
//    {
//        return DialogAction::make();
//    }
//
//    /**
//     * @return \warm\admin\renderer\expand\ImageToolbarAction
//     */
//    public function ImageToolbarAction(): \warm\admin\renderer\expand\ImageToolbarAction
//    {
//        return \warm\admin\renderer\expand\ImageToolbarAction::make();
//    }
//
//    /**
//     * @return \warm\admin\renderer\expand\LinkAction
//     */
//    public function LinkAction(): \warm\admin\renderer\expand\LinkAction
//    {
//        return \warm\admin\renderer\expand\LinkAction::make();
//    }
//
//
//    /**
//     * @return \warm\admin\renderer\expand\UrlAction
//     */
//    public function UrlAction(): \warm\admin\renderer\expand\UrlAction
//    {
//        return \warm\admin\renderer\expand\UrlAction::make();
//    }
}